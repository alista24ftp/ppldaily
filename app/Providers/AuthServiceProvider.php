<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Article' => 'App\Policies\ArticlePolicy',
        'App\Models\ArticleCategory' => 'App\Policies\ArticleCategoryPolicy',
        'App\Models\Ad' => 'App\Policies\AdPolicy',
        'App\Models\AdType' => 'App\Policies\AdTypePolicy',
        'App\Models\Comment' => 'App\Policies\CommentPolicy',
        'App\Models\SiteInfo' => 'App\Policies\SiteInfoPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Visitor' => 'App\Policies\VisitorPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::before(function($user, $ability){
            if($user->user_blacklisted || !$user->email_verified_at) return false;
        });
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addHours(2));
    }
}
