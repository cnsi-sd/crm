<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Channel\DefaultAnswer;
use App\Models\User\Role;
use App\Models\User\User;
use App\Policies\Configuration\Permission\DefaultAnswerPolicy;
use App\Policies\Settings\Permissions\RolePolicy;
use App\Policies\Settings\Permissions\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
        DefaultAnswer::class => DefaultAnswerPolicy::class
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

        //
    }
}
