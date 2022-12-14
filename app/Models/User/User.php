<?php

namespace App\Models\User;

use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Enums\ColumnTypeEnum;
use App\Enums\FixedWidthEnum;
use App\Models\Channel\Channel;
use App\Models\User\Role;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property boolean $active
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Thread[] $threads
 * @property Message[] $messages
 * @property Role[] $roles
 */

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function channel_users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false)
            ->setSortable(false);

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.user.name'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setKey('name')
            ->setSortable(false);

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.user.email'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setKey('email')
            ->setSortable(false);

        $columns[] = (new TableColumnBuilder())
            ->setLabel(trans_choice('app.role.role', 2))
            ->setType(ColumnTypeEnum::SELECT)
            ->setOptions(Role::getRolesNames())
            ->setCallback(function (User $user) {
                return $user->role->name;
            })
            ->setKey('role_id')
            ->setSortable(false);

        $columns[] = TableColumnBuilder::boolean()
            ->setFixedWidth(FixedWidthEnum::XL)
            ->setLabel(__('app.user.active'))
            ->setKey('active')
            ->setSortable(false);

        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (User $user) {
                return view('admin.users.inline_table_actions')
                    ->with('user', $user);
            });

        return $columns;
    }

    public function isAdmin(): bool
    {
        /** get the user role */
        $role = Role::getById($this->role_id);

        if ($role->name == "admin") {
            return true;
        } else {
            return false;
        }
    }

    public function hasPermission(string $permission): bool
    {
        /** get the user role */
        $role = Role::getById($this->role_id);

        /** get the user authorizations */
        $user_permissions = explode(';', $role->permissions);

        return in_array($permission, $user_permissions);
    }

    public static function getUserByChannel(Channel $channel): User
    {
        //TODO : mapping Channel X User
        return User::query()
            ->select('user_id')
            ->join('channel_users', 'channel_users.user_id', 'users.id')
            ->where('channel_users.channel_id', $channel->id)
            ->first();
    }
}
