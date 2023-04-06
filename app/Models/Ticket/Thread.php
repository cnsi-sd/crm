<?php

namespace App\Models\Ticket;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Ticket\Revival\Revival;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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
 * @property Datetime $created_at
 * @property Datetime $updated_at
 * @property array $channel_data
 *
 * @property Ticket $ticket
 * @property Collection|Message[] $messages
 * @property Revival $revival
 */
class Thread extends Model
{
    protected $table = 'ticket_threads';

    const DEFAULT_CHANNEL_NUMBER = 'crm_default_thread';
    const DEFAULT_NAME = 'Fil de discussion principal';

    protected $fillable = [
        'ticket_id',
        'revival_id',
        'revival_start_date',
        'revival_message_count',
        'channel_thread_number',
        'name',
        'channel_data',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'revival_start_date' => 'date',
        'channel_data' => 'array'
    ];

    /**
     * create or get thread
     * @param Ticket $ticket
     * @param string $channel_thread_number
     * @param string $name
     * @param array $channel_data
     * @return Model|Thread
     */
    public static function getOrCreateThread(Ticket $ticket, string $channel_thread_number, string $name, $channel_data = [] ): Model|Thread
    {
        return Thread::query()
            ->select('ticket_threads.*')
            ->join('tickets', 'tickets.id', 'ticket_threads.ticket_id')
            ->where('ticket_threads.channel_thread_number', $channel_thread_number)
            ->where('tickets.channel_id', $ticket->channel_id)
            ->firstOrCreate(
                [
                    'channel_thread_number' => $channel_thread_number,
                    'ticket_id' => $ticket->id,
                ],
                [
                    'name' => $name,
                    'channel_data' => json_encode($channel_data),
                ],
            );
    }

    public function messages(): HasMany
    {
        // Prefer the order by created_at, because the migration process could have imported messages in the wrong order.
        return $this->hasMany(Message::class)->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');
    }

    public function firstMessage(): ?Message
    {
        // Prefer the order by created_at, because the migration process could have imported messages in the wrong order.
        return $this->messages()->reorder('created_at', 'ASC')->first();
    }

    public function lastMessage(): ?Message
    {
        // Prefer the order by created_at, because the migration process could have imported messages in the wrong order.
        return $this->messages()->reorder('created_at', 'DESC')->first();
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function revival(): BelongsTo
    {
        return $this->belongsTo(Revival::class);
    }

    public function isRevivalSelected($revival_id): bool
    {
        return $this->revival_id === $revival_id;
    }

    /**
     * This function return a boolean if thread_revival haven't error otherwise return a string with a message error
     * @param bool $check_dates
     * @return bool|string
     * @throws Exception
     */
    public function getThreadRevivalError(bool $check_dates = true): bool|string
    {
        $revival = $this->revival;
        if (!$revival)
            return false;

        $starterMessage = 'Relance automatique non applicable : ';
        $endMessage = '';

        // check message
        if (!$revival->default_answer)
            $endMessage = 'Configuration de la relance auto incomplète : le champs `Réponse par défaut` es invalide';

        //check frequency
        if ($revival->frequency <= 0)
            $endMessage = 'Configuration de la relance auto incomplète : le champs `Fréquence des relances` es invalide';

        //check last message parameters
        $lastMessage = $this->lastMessage();
        if (!$lastMessage || $lastMessage->author_type !== TicketMessageAuthorTypeEnum::ADMIN)
            $endMessage = 'Le dernier message doit être écrit par un administrateur';

        if ($this->ticket->state !== TicketStateEnum::WAITING_CUSTOMER)
            $endMessage = 'Le ticket doit être en Attente client';

        //check the nex revival date
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

    /**
     * Check if the start date is greater than the current date otherwise returns the date of the next revival send
     * @return DateTime
     * @throws Exception
     */
    public function getNextRevivalDate(): DateTime
    {
        // Revival start date
        if ($this->revival_start_date && $this->revival_start_date->getTimestamp() > time())
            return $this->revival_start_date;

        // Revival frequency
        $lastMessageDate = clone $this->lastMessage()->updated_at;
        $freq = $this->revival->frequency;
        $interval = new DateInterval('P' . $freq . 'D');
        $lastMessageDate->add($interval);

        return $lastMessageDate;
    }

    public function getUnreadMessages(): int
    {
        $numberOfUnreadMessages = 0;
        foreach ($this->messages()->get() as $message) {
            if ($message->isExternal()) {
                $numberOfUnreadMessages += 1;
            } else {
                break;
            }
        }
        return $numberOfUnreadMessages;
    }

}
