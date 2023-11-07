<?php

namespace App\Providers;

use App\Models\Passport\RefreshToken;
use App\Models\Passport\Token;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return config('app.frontend_url') . '?token=' . $token;
        });
    }
}
