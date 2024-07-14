<?php

namespace App\Providers;

use App\Packages\JWT\JWT;
use App\Packages\JWT\JWTGuard;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
        Auth::extend(
            driver: 'jwt',
            callback: fn($app, $name, array $config) => new JWTGuard(
                provider: Auth::createUserProvider(provider: $config['provider']),
                request: $app->make('request')
            )
        );

        App::bind('JWT', fn() => new JWT());
    }
}
