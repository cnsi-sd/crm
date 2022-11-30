<?php

namespace App\Models\Ticket;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $thread_id
 * @property int $user_id
 * @property string $content
 * @property boolean $displayed
 * @property string $type
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Thread $thread
 */

class Comment extends Model
{
    protected $table = 'ticket_thread_comments';

    protected $fillable = [
      'thread_id',
      'user_id',
      'content',
      'displayed',
      'type',
      'created_at',
      'updated_at'
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
