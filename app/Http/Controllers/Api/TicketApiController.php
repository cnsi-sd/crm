<?php

namespace App\Http\Controllers\Api;

use App\Enums\Ticket\TicketStateEnum;
use App\Models\Tags\Tag;
use App\Models\Ticket\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class TicketApiController extends AbstractApiController
{
    protected $logger_channel = 'api/in/ticket/savProcessComplete.log';
    public function savProcessComplete(Request $request, string $token, Ticket $ticket) : JsonResponse
    {
        try {
            $this->logger->info('--- START : updating ticket ' . $ticket->id . ' after sav process completed by customer ---');
            if($token !== setting('savprocess.api_token')) {
                $this->logger->info('--- TOKEN NOT DEFINED ---');
                return $this->message('Token not defined', 500, ['status' => 'error']);
            }

            if(!setting('savprocess.active')) {
                $this->logger->info('--- SAV Process not active ---');
                return $this->message('Inactive SAV Process', 500, ['status' => 'error']);
            }

            $ticket->deadline = date('Y-m-d');
            $ticket->state = TicketStateEnum::OPENED;
            $tagId = setting('savprocess.complete_tag_id');
            $tag = Tag::findOrFail($tagId);
            $ticket->addTag($tag);
            $this->stopTicketRevival($ticket);
            $ticket->save();
            $this->logger->info('--- DONE ---');
            return $this->message('Ticket Updated', 200, ['status' => 'success']);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            return $this->message('Internal Server Error', 500, ['status' => 'error']);
        }
    }

    public function stopTicketRevival(Ticket $ticket)
    {
        $revivalIdToDelete = explode(',',setting('savprocess.stop_revival_ids'));
        foreach ($ticket->threads as $thread) {
            if(in_array($thread->revival_id, $revivalIdToDelete)) {
                $thread->stopRevival();
            }
        }
    }
}
