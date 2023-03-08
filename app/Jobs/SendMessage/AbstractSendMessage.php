<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Message;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class AbstractSendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Message $message;
    protected Channel $channel;
    protected string $testOrder;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
    protected function translateContent($content): string
    {
        $content = str_replace(['<br>', '<br/>', '<br />'], "\n", $content);
        $content = html_entity_decode($content);
        return $content;
    }

    abstract public function handle(): void;

    /**
     * @throws Exception
     */
    public static function dispatchMessage(Message $message)
    {
        match($message->thread->ticket->channel->name) {
            ChannelEnum::BUT_FR             => ButSendMessage::dispatch($message),
            ChannelEnum::CARREFOUR_FR       => CarrefourSendMessage::dispatch($message),
            ChannelEnum::CONFORAMA_FR       => ConforamaSendMessage::dispatch($message),
            ChannelEnum::DARTY_COM          => DartySendMessage::dispatch($message),
            ChannelEnum::INTERMARCHE_FR     => IntermarcheSendMessage::dispatch($message),
            ChannelEnum::LAPOSTE_FR         => LaposteSendMessage::dispatch($message),
            ChannelEnum::E_LECLERC          => LeclercSendMessage::dispatch($message),
            ChannelEnum::METRO_FR           => MetroSendMessage::dispatch($message),
            ChannelEnum::RUEDUCOMMERCE_FR   => RueducommerceSendMessage::dispatch($message),
            ChannelEnum::SHOWROOMPRIVE_COM  => ShowroomSendMessage::dispatch($message),
            ChannelEnum::UBALDI_COM         => UbaldiSendMessage::dispatch($message),
            ChannelEnum::CDISCOUNT_FR       => CdiscountSendMessage::dispatch($message),
            ChannelEnum::FNAC_COM           => FnacSendMessage::dispatch($message),
            ChannelEnum::ICOZA_FR           => IcozaSendMessage::dispatch($message),
            ChannelEnum::RAKUTEN_COM        => RakutenSendMessage::dispatch($message),
            default => throw new Exception('Channel given does not exists.'),
        };
    }
}
