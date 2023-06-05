<?php

namespace App\Http\Controllers\Api;


use App\Enums\Ticket\TicketAvisDeSouffranceTypeEnum;
use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvisDeSouffranceApiController extends AbstractApiController
{

    public function add_avis_de_souffrance(Request $request): JsonResponse
    {
        try {
            if ($request->header('token') !== env('NOTICE_OF_SUFFERING_TOKEN')) {
                $this->logger->info('--- TOKEN is not available ---');
                return $this->message('TOKEN is not available', 401, ['status' => 'error']);
            }
            $validator = Validator::make($request->all(), [
                'channel' => ['required', 'string'],
                'num_command' => ['required', 'string'],
                'message_souffrance' => ['required', 'string'],
            ]);

            // return HTTP 400 if validation fails
            if ($validator->fails()) {
                $this->logger->info('Validation failed');
                return $this->message($validator->messages(), 400, ['status' => 'error']);
            }

            //transaction
            $channel = (new Channel())->getByName($request->input('channel'));
            $order = Order::getOrder($request->input('num_command'), $channel);
            $ticket = Ticket::getTicket($order, $channel);
            Comment::getOrCreate($ticket->id, 'Raison : ' . $request->input('message_souffrance'), TicketCommentTypeEnum::INFO_IMPORTANT);

            $tagId = setting('ticket.notice_of_suffering');
            $tag = Tag::findOrFail($tagId);
            $ticket->addTag($tag);

            return $this->message("Notice of suffering has been added", 200, ['status' => 'success']);

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            return $this->message('Internal Server Error', 500, ['status' => 'error']);
        }
    }

}
