<?php

namespace App\Models\Channel;


use App\Models\Ticket\Ticket;
use App\Models\User\Channel_Users;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $user_id
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

    public function channel_users(): HasMany
    {
        return $this->hasMany(Channel_Users::class);
    }

    public static function getByName(string $name, bool $filter_active = true) : Channel
    {
        /** @var Channel $channel */
        $channel = self::query()->where('name', $name)->first();
        if(!$channel)
            throw new Exception('Channel `' . $name . '` does not exists');

        return $channel;
    }
}
