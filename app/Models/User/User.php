<?php

namespace App\Models\User;

use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Enums\ColumnTypeEnum;
use App\Enums\FixedWidthEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\History;
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
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $password
 * @property boolean $active
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Thread[] $threads
 * @property Message[] $messages
 * @property History[] $historicals
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
        'firstname',
        'lastname',
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

    public static function getUsersNames(): array
    {
        /** @var User[] $users */
        $users = self::query()
            ->orderBy('firstname', 'ASC')
            ->orderBy('lastname', 'ASC')
            ->get();

        $names = [];
        foreach($users as $user) {
            $names[$user->id] = $user->__toString();
        }

        return $names;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function channel_users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function historicals(): HasMany
    {
        return $this->hasMany(History::class);
    }
    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false)
            ->setSortable(false);

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.user.firstname'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setKey('firstname')
            ->setSortable(false);

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.user.lastname'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setKey('lastname')
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
    public function hasPermission(string $permission): bool
    {
        /** get the user role */
        $role = Role::getById($this->role_id);

        /** get the user authorizations */
        $user_permissions = $role->getPermissions();

        return in_array($permission, $user_permissions);
    }

    public static function getUserByChannel(Channel $channel): User
    {
        return User::query()
            ->select('user_id')
            ->join('channel_users', 'channel_users.user_id', 'users.id')
            ->where('channel_users.channel_id', $channel->id)
            ->first();
    }

    public function __toString(): string
    {
        return $this->firstname . ' ' . substr($this->lastname, 0, 1) . '.';
    }
}
