<?php

namespace App\Models\Ticket;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 *
 * @property Thread $thread
 */

class Comment extends Model
{
    protected $table = 'ticket_comments';

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
