<?php

namespace App\Models\Ticket;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $ticket_id
 * @property string $channel_thread_number
 * @property string $name
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Comment[] $comments
 * @property Ticket $ticket
 * @property Message[] $messages
 */

class Thread extends Model
{
    protected $table = 'ticket_threads';

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
