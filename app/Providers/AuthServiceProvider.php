<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Channel\DefaultAnswer;
use App\Models\Tags\Tag;
use App\Models\Ticket\Revival\Revival;
use App\Models\User\Role;
use App\Models\User\User;
use App\Models\Channel\Channel;
use App\Policies\Configuration\Permission\DefaultAnswerPolicy;
use App\Policies\Configuration\Permission\RevivalPolicy;
use App\Policies\Configuration\Permission\ChannelPolicy;
use App\Models\Ticket\Ticket;
use App\Policies\Configuration\Permission\TagPolicy;
use App\Policies\Settings\Permissions\RolePolicy;
use App\Policies\Settings\Permissions\UserPolicy;
use App\Policies\Tickets\TicketPolicy;
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
        DefaultAnswer::class => DefaultAnswerPolicy::class,
        Revival::class => RevivalPolicy::class,
        Ticket::class => TicketPolicy::class,
        Channel::class => ChannelPolicy::class,
        Tag::class => TagPolicy::class,
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
