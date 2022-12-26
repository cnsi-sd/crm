<?php

namespace App\Models\Ticket;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Models\Ticket\Revival\Revival;
use App\Models\User\User;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use League\CommonMark\Node\Inline\Text;
use Mirakl\MMP\Common\Domain\Collection\Message\Thread\ThreadRecipientCollection;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadRecipient;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadReplyMessageInput;
use Mirakl\MMP\Common\Request\Message\ThreadReplyRequest;

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

    public function getCredentials ($channel){
        switch($channel){
            case(ChannelEnum::BUT_FR):
                return [
                    'API_URL' => env('BUT_API_URL'),
                    'API_KEY' => env('BUT_API_KEY'),
                    'API_SHOP_ID' => env('BUT_API_SHOP_ID'),
                ];
                break;
        }
    }

    public function sendReplyRevival($channel,Thread $thread,Revival $revival, $message, $sendTo){
        $this->getCredentials($channel);
        $client = $this->initApiClient();

        // send on the MP link api
        $recipients = new ThreadRecipientCollection();
        foreach ( $sendTo as $type){
            $rec = new ThreadRecipient();
            $rec->setType($type);
            $recipients->add($rec);
        }

        $messageToAnswer = new ThreadReplyMessageInput();
        $messageToAnswer->setTo($recipients);
        $messageToAnswer->setBody($message->content);

        $request = new ThreadReplyRequest($thread->id, $messageToAnswer);

        // save local send to display on thread
        $messageBD = new Message();
        $messageBD->thread_id = $thread->id;
        $messageBD->user_id = null;
        $messageBD->channel_message_number = null; // todo : definir comment recuperer
        $messageBD->author_type = self::getAuthorType(TicketMessageAuthorTypeEnum::ADMIN);
        $messageBD->content = $message->content;

        $messageBD->save();
        $client->replyToThread($request);
    }


}

