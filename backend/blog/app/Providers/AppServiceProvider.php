<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\UserRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('registration', function (Request $request): Limit {
            return Limit::perMinute(5)->by($request->ip());
        });

        Password::defaults(function (): Password {
            $rule = Password::min(8)->letters()->mixedCase()->numbers();

            return $this->app->environment('production')
                ? $rule->symbols()->uncompromised()
                : $rule;
        });
    }
}
