<?php

namespace App\Providers;

use App\Enums\PermissionEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use App\Models\Channel\SavNote;
use App\Models\Tags\Tag;
use App\Models\Ticket\Revival\Revival;
use App\Models\Ticket\Ticket;
use App\Models\User\Role;
use App\Models\User\User;
use App\Policies\Configuration\ChannelPolicy;
use App\Policies\Configuration\DefaultAnswerPolicy;
use App\Policies\Configuration\RevivalPolicy;
use App\Policies\Configuration\SavNotePolicy;
use App\Policies\Configuration\TagPolicy;
use App\Policies\Settings\JobPolicy;
use App\Policies\Settings\RolePolicy;
use App\Policies\Settings\UserPolicy;
use App\Policies\Tickets\TicketPolicy;
use Cnsi\JobWatcher\Models\Job;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        SavNote::class => SavNotePolicy::class,
        Job::class => JobPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('bot_config', function (User $user) {
            return $user->hasPermission(PermissionEnum::BOT_CONFIG);
        });

        Gate::define('misc_config', function (User $user) {
            return $user->hasPermission(PermissionEnum::MISC_CONFIG);
        });

        Gate::define('agent_doc', function (User $user) {
            return $user->hasPermission(PermissionEnum::AGENT_DOC);
        });

        Gate::define('admin_doc', function (User $user) {
            return $user->hasPermission(PermissionEnum::ADMIN_DOC);
        });
    }
}
