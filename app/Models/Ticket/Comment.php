<?php

namespace App\Models\Ticket;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Node\Inline\Text;

/**
 * @property int $id
 * @property int $thread_id
 * @property int $user_id
 * @property Text $content
 * @property boolean $displayed
 * @property string $type
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */

class Comment extends Model
{

    protected $table = 'ticket_comments';
}
