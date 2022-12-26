<?php

namespace App\Models\Ticket;

use App\Models\User\User;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use League\CommonMark\Node\Inline\Text;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;

/**
 * @property int $id
 * @property int $thread_id
 * @property int $user_id
 * @property string $channel_message_number
 * @property string $author_type
 * @property string $content
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Thread $thread
 * @property User $user
 */

class Message extends Model
{
    /**
     * @var false|mixed
     */
    protected $table = 'ticket_thread_messages';

    protected $fillable = [
      'thread_id',
      'user_id',
      'channel_message_number',
      'author_type',
      'content',
      'created_at',
      'updated_at'
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



}

