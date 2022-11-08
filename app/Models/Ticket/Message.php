<?php

namespace App\Models\Ticket;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $thread_id
 * @property int $user_id
 * @property string $channel_message_number
 * @property string $author_type
 * @property boolean $private
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */

class Message extends Model
{
}
