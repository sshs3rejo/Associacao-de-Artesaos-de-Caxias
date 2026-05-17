<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS URLs in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Carregar configurações dinâmicas da Associação do arquivo JSON
        if (file_exists(storage_path('app/settings.json'))) {
            $customSettings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
            if (is_array($customSettings)) {
                foreach ($customSettings as $key => $value) {
                    config(['association.' . $key => $value]);
                }
            }
        }
    }
}
