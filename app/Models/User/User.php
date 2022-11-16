<?php

namespace App\Models\User;

use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
class User extends Model
{
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'active',
        'created_at',
        'updated_at'
    ];

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }



    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
