<?php

namespace App\Models\Ticket;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

/**
 * @property int $id
 * @property int $channel_id
 * @property int $order_id
 * @property int $user_id
 * @property string $state
 * @property string $priority
 * @property Date $deadline
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */

class Ticket extends Model
{

}
