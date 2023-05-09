<?php

namespace App\Http\Controllers\Api;


use App\Enums\Ticket\TicketAvisDeSouffranceTypeEnum;
use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvisDeSouffranceApiController extends AbstractApiController
{

    public function add_avis_de_souffrance(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'channel' => ['required', 'string'],
                'num_command' => ['required', 'string'],
                'message_souffrance' => ['required', 'string'],
            ]);
            $channel = (new Channel())->getByName($request->input('channel'));
            $order = Order::getOrder($request->input('num_command'), $channel);
            $ticket = Ticket::getTicket($order, $channel);
            $thread = Thread::getOrCreateThread($ticket,
                Thread::DEFAULT_CHANNEL_NUMBER,
                TicketAvisDeSouffranceTypeEnum::AVIS_DE_SOUFFRANCE . " - " . $request->input('num_command'));
            $comment = Comment::getOrCreate($ticket->id, $request->input('message_souffrance'), TicketCommentTypeEnum::INFO_IMPORTANT);

            /*$tagId = setting('ticket.notice_of_suffering');
            $tag = Tag::findOrFail($tagId);
            $ticket->addTag($tag);*/
            return $this->message("Notice of suffering has been added", 200, ['status' => 'success']);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            return $this->message('Internal Server Error', 500, ['status' => 'error']);
        }
    }

}
