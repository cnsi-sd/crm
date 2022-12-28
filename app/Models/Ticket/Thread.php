<?php

namespace App\Models\Ticket;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Ticket\Revival\Revival;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $ticket_id
 * @property DateTime $revival_start_date
 * @property int $revival_message_count
 * @property string $channel_thread_number
 * @property string $name
 * @property string $customer_issue
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Comment[] $comments
 * @property Ticket $ticket
 * @property Message[] $messages
 * @property Revival $revival
 */

class Thread extends Model
{
    protected $table = 'ticket_threads';

    protected $fillable = [
        'ticket_id',
        'revival_id',
        'revival_start_date',
        'revival_message_count',
        'channel_thread_number',
        'name',
        'customer_issue',
        'created_at',
        'updated_at'
    ];

    public static function getThread(Ticket $ticket, string $channel_thread_number, string $name, string $customer_issue)
    {
        return Thread::query()
            ->join('tickets', 'tickets.id', 'ticket_threads.ticket_id')
            ->where('ticket_threads.channel_thread_number', $channel_thread_number)
            ->where('tickets.channel_id', $ticket->channel_id)
            ->firstOrCreate(
                [
                    'channel_thread_number' => $channel_thread_number,
                ],
                [
                    'ticket_id' => $ticket->id,
                    'channel_thread_number' => $channel_thread_number,
                    'name'=> $name,
                    'customer_issue' => $customer_issue,
                ],
            );
    }

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

    public function revival(): BelongsTo
    {
        return $this->belongsTo(Revival::class);
    }

    public function throwableRevival(?Revival $threadRevival){
        $revivalError = false;
        if($threadRevival) {
            try {
                $this->isAllowableForRevival();
            } catch (Exception $e) {
                $revivalError = $e->getMessage();
            }
        }
        return $revivalError;
    }

    public function isAllowableForRevival($revival = null, $check_date = true)
    {
        try {
            if (is_null($revival))
                $revival = $this->revival;

            $defaultReply = $revival->default_answer->id;
            if (empty($defaultReply))
                throw new Exception('Configuration de la relance auto incomplète : le champs `Réponse par défaut` es invalide');

            if ($revival->frequency <= 0)
                throw new Exception('Configuration de la relance auto incomplète : le champs `Fréquence des relances` es invalide');

            $lastMessage = $this->getLastMessage($this);
            if (!$lastMessage || !$lastMessage->id || $lastMessage->author_type !== TicketMessageAuthorTypeEnum::ADMIN)
                throw new Exception('Le dernier message doit être écrit par un administrateur');

            if ($this->ticket->state !== TicketStateEnum::WAITING_CUSTOMER)
                throw new Exception('Le ticket doit être en Attente client');

        } catch (Exception $exception) {
            throw new Exception('Relance automatique non applicable au thread #' . $this->id . ' : ' . $exception->getMessage());
        }
    }

    public function getLastMessage($thread): ?Message
    {
        // todo : voir tri object
        return Message::query()
            ->where('thread_id', $thread->id)
            ->orderBy('created_at', 'DESC')
            ->first();
    }


}
