<?php

namespace App\Models\Channel;


use App\Models\Ticket\Revival\Revival;
use App\Models\Ticket\Ticket;
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
 * @property DefaultAnswer $defaultAnswers
 * @property Revival[] $revivals
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

    public function defaultAnswers(): HasMany
    {
        return $this->hasMany(DefaultAnswer::class);
    }

    public function revivals(): HasMany
    {
        return $this->hasMany(Revival::class);
    public static function getChannelsNames(): array
    {
        return self::query()->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public static function getByName(string $name, bool $filter_active = true) : Channel
    {
        /** @var Channel $channel */
        $channel = self::query()->where('name', $name)->first();
        if(!$channel)
            throw new Exception('Channel `' . $name . '` does not exists');

        return $channel;
    }

    public static function getChannelNames(): array
    {
        return self::query()->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();
    }
}
