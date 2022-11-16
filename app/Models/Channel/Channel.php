<?php

namespace App\Models\Channel;


use App\Models\Ticket\Ticket;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Ticket[] $tickets
 * @property Order[] $orders
 */
class Channel extends Model
{
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
