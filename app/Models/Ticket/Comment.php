<?php

namespace App\Models\Ticket;

use App\Models\User\User;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $content
 * @property boolean $displayed
 * @property string $type
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Ticket $ticket
 */
class Comment extends Model
{
    protected $table = 'ticket_comments';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'content',
        'displayed',
        'type',
        'created_at',
        'updated_at'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getOrCreate(int $ticket_id, string $content, string $type, int|null $user_id = null)
    {
        return Comment::firstOrCreate(
            [
                'ticket_id' => $ticket_id,
                'content' => $content
            ],
            [
                'user_id' => $user_id,
                'displayed' => 1,
                'type' => $type,
            ]);
    }
}
