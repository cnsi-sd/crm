<?php

namespace App\Models\Ticket;

use App\Enums\CrmDocumentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Models\User\User;
use Cnsi\Attachments\Trait\Documentable;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    use Documentable;

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExternal(): bool
    {
        return in_array($this->author_type, [TicketMessageAuthorTypeEnum::CUSTOMER, TicketMessageAuthorTypeEnum::OPERATOR]);
    }

    public function isFirstMessageOnThread(): bool
    {
        return $this->thread->firstMessage()->id === $this->id;
    }

    public function hasBeenAnswered(): bool
    {
        return $this->thread->messages()
            ->where('id', '>', $this->id)
            ->whereIn('author_type', [TicketMessageAuthorTypeEnum::SYSTEM, TicketMessageAuthorTypeEnum::ADMIN])
            ->count() > 0;
    }

    protected function getAllowedDocumentTypes(): array
    {
        return [
            CrmDocumentTypeEnum::CUSTOMER_SERVICE_REPORT,
            CrmDocumentTypeEnum::CUSTOMER_SERVICE_STATION,
            CrmDocumentTypeEnum::CLIENT_BANK_ACCOUNT_NUMBER,
            CrmDocumentTypeEnum::CUSTOMER_FILING,
            CrmDocumentTypeEnum::PRODUCT_PHOTO,
            CrmDocumentTypeEnum::OTHER,
        ];
    }
}

