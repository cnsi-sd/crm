<?php

namespace App\Models\Ticket;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Ticket\Revival\Revival;
use DateInterval;
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

    protected $casts = [
        'revival_start_date' => 'date',
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
                    'name' => $name,
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

    public function getThreadRevivalError(bool $check_dates = true): bool|string
    {
        $revival = $this->revival;
        if (!$revival)
            return false;

        $starterMessage = 'Relance automatique non applicable : ';
        $endMessage = '';

        // Verification du message
        if (!$revival->default_answer)
            $endMessage = 'Configuration de la relance auto incomplète : le champs `Réponse par défaut` es invalide';

        //verification de la frequence
        if ($revival->frequency <= 0)
            $endMessage = 'Configuration de la relance auto incomplète : le champs `Fréquence des relances` es invalide';

        //verification des parametres du dernier message
        $lastMessage = $this->getLastMessage();
        if (!$lastMessage || $lastMessage->author_type !== TicketMessageAuthorTypeEnum::ADMIN)
            $endMessage = 'Le dernier message doit être écrit par un administrateur';

        if ($this->ticket->state !== TicketStateEnum::WAITING_CUSTOMER)
            $endMessage = 'Le ticket doit être en Attente client';

        //verification de la date d'execution du prochain revival
        if ($check_dates) {
            // Check next revival date
            $current_time = time();
            $next_revival_time = $this->getNextRevivalDate()->getTimestamp();
            if ($next_revival_time > $current_time)
                $endMessage = "La date de la prochaine relance n'est pas atteinte";
        }

        if(empty($endMessage))
            return false;
        else
            return $starterMessage . $endMessage;
    }

    public function getNextRevivalDate(): DateTime
    {
        // Revival start date
        if ($this->revival_start_date && $this->revival_start_date->getTimestamp() > time())
            return $this->revival_start_date;

        // Revival frequency
        $lastMessageDate = clone $this->getLastMessage()->updated_at;
        $freq = $this->revival->frequency;
        $interval = new DateInterval('P' . $freq . 'D');
        $lastMessageDate->add($interval);

        return $lastMessageDate;
    }

    public function getLastMessage(): ?Message
    {
        return Message::query()
            ->where('thread_id', $this->id)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function getFrequencyInSeconds(Revival $revival): float|int
    {
        return $revival->frequency * 24 * 3600;
    }
}
