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
    }
}
