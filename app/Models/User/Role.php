<?php

namespace App\Models\User;

use App\Models\Ticket\Message;
use DateTime;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property User[] $users
 */

class Role extends Model
{
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
