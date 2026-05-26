<?php

namespace App\Providers;

use App\Models\CategoriasProdutos;
use App\Models\User;
use App\Observers\CategoriaProdutosObserver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Ensure storage symlink exists (Railway/cloud deploys)
        $publicStorage = public_path('storage');
        if (!file_exists($publicStorage)) {
            try {
                app('files')->link(storage_path('app/public'), $publicStorage);
            } catch (\Throwable) {}
        }

        // Force HTTPS URLs in production (except localhost)
        if (config('app.env') === 'production' && !str_starts_with(config('app.url'), 'http://localhost')) {
            URL::forceScheme('https');
        }

        // Carregar configurações dinâmicas da Associação
        if (Schema::hasTable('settings')) {
            $settings = \App\Models\Setting::getAll();
            foreach ($settings as $key => $value) {
                config(['association.' . $key => $value]);
            }
        } elseif (file_exists(storage_path('app/settings.json'))) {
            $customSettings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
            if (is_array($customSettings)) {
                foreach ($customSettings as $key => $value) {
                    config(['association.' . $key => $value]);
                }
            }
        }

        CategoriasProdutos::observe(CategoriaProdutosObserver::class);

        if (Schema::hasTable('users') && !User::where('role', 'admin')->exists()) {
            User::create([
                'name' => config('admin.name'),
                'email' => config('admin.email'),
                'password' => Hash::make(config('admin.password')),
                'role' => 'admin',
                'is_active' => true,
            ]);
        }
    }
}
