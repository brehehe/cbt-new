<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Models\Company\Company;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }

        Blade::directive('number', function ($expression) {
            return "<?php echo number_format($expression, 0, ',', '.'); ?>";
        });

        view()->composer('*', function ($view) {
            $company = Company::first(); // atau where('slug', config('app.name_slug'))

            $view->with('companyData', $company);
        });

        // Global Activity Log Metadata Injection
        \Spatie\Activitylog\Models\Activity::saving(function ($activity) {
            // Only inject if not already present
            if (!isset($activity->properties['ip_address'])) {
                $ip = request()->ip() ?? '127.0.0.1';
                
                // Attempt GeoIP lookup
                $location = null;
                try {
                    // Skip location for local IPs to avoid errors/delays
                    if ($ip !== '127.0.0.1' && $ip !== '::1') {
                        $position = \Stevebauman\Location\Facades\Location::get($ip);
                        if ($position) {
                            $location = [
                                'country' => $position->countryName,
                                'city' => $position->cityName,
                                'iso_code' => $position->countryCode,
                                'timezone' => $position->timezone,
                            ];
                        }
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('GeoIP lookup failed: ' . $e->getMessage());
                }

                $activity->properties = $activity->properties->merge([
                    'ip_address' => $ip,
                    'user_agent' => request()->userAgent() ?? 'System / CLI',
                    'location'   => $location,
                    'url'        => request()->fullUrl(),
                    'method'     => request()->method(),
                ]);
            }
        });
    }
}
