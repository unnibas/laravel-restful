<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
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

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        //Passport::enableImplicitGrant();

        Passport::tokensCan([
            'purchase-product' => 'Ceate a new transaction for a specific product',
            'manage-product' => 'Create, read, update and delete product',
            'manage-account' => 'read your account  data, id, name, email, if verified, and if admin (cannot read password). Modify your account data (email and password). cannot delete your account',
            'read-general' => 'Read general information like purchasing categories, purchased products, selling products, selling categories, your transactions (purchases and sales)',
        ]);
    }
}
