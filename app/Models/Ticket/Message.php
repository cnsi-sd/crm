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
 * @property Text $content
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
    protected $table = 'ticket_threads_messages';

    const FROM_SHOP_TYPE = 'SHOP_USER';

    protected $fillable = [
      'thread_id',
      'user_id',
      'channel_message_number',
      'author_type',
      'content',
      'created_at',
      'updated_at'
    ];

    private static function ifIsShopUser($type): bool
    {
        if (self::FROM_SHOP_TYPE === $type) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param ThreadMessage $api_message
     * @param $thread_id
     * @return Message
     */
    public static function convertApiResponseToModel($api_message, $thread_id): Message
    {
        $isShop_User = self::ifIsShopUser($api_message->getFrom()->getType());

        $message = new Message();
        if (!$isShop_User) {
            $message = Message::firstOrCreate([
                'channel_message_number' => $api_message->getId(),
            ], [
                'thread_id' => $thread_id,
                'user_id' => 1,
                'channel_message_number' => $api_message->getId(),
                'author_type' => 'client',
                'content' => strip_tags($api_message->getBody()),
                'created_at' => $api_message->getDateCreated()->format('Y-m-d H:i:s'),
            ]);
        }
        return $message;
    }


    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}

