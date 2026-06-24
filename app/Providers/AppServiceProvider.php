<?php

namespace App\Providers;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Stevebauman\Location\Facades\Location;

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
        // Prevent lazy loading in non-production environments to detect N+1 queries
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(! $this->app->isProduction());

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
        Activity::saving(function ($activity) {
            // Only inject if not already present
            if (! isset($activity->properties['ip_address'])) {
                $ip = request()->ip() ?? '127.0.0.1';

                // Attempt GeoIP lookup
                $location = null;
                try {
                    // Skip location for local IPs to avoid errors/delays
                    if ($ip !== '127.0.0.1' && $ip !== '::1') {
                        $cacheKey = 'geoip_location_' . md5($ip);
                        $location = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addDays(30), function () use ($ip) {
                            $position = Location::get($ip);
                            if ($position) {
                                return [
                                    'country' => $position->countryName,
                                    'city' => $position->cityName,
                                    'iso_code' => $position->countryCode,
                                    'timezone' => $position->timezone,
                                ];
                            }
                            return null;
                        });
                    }
                } catch (\Throwable $e) {
                    Log::warning('GeoIP lookup failed: '.$e->getMessage());
                }

                $activity->properties = $activity->properties->merge([
                    'ip_address' => $ip,
                    'user_agent' => request()->userAgent() ?? 'System / CLI',
                    'location' => $location,
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                ]);
            }
        });
    }
}
