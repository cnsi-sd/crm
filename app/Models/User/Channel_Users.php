<?php

namespace App\Models\User;

use App\Models\Channel\Channel;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $channel_id
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Channel $channel
 * @property User $user
 */
class Channel_Users extends Model
{
    protected $table = 'channel_users';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
