<?php

namespace App\Jobs\SendMessage;

use App\Models\Channel\Channel;
use App\Models\Ticket\Message;
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
}
