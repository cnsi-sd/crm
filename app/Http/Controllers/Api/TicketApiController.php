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
            if($token === env('SAVPROCESS_CRM_TOKEN')) {
                $ticket->deadline = date('Y-m-d');
                $ticket->state = TicketStateEnum::OPENED;
                $tagId = setting('savprocesscomplete_tag_id');
                $tag = Tag::findOrFail($tagId);
                $ticket->addTag($tag);
                $this->stopTicketRevival($ticket);
                $ticket->save();
                $this->logger->info('--- DONE ---');
                return $this->message('Ticket Updated', 200, ['status' => 'success']);
            } else {
                $this->logger->info('--- TOKEN NOT DEFINED ---');
                return $this->message('Token not defined', 500, ['status' => 'error']);
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            return $this->message('Internal Server Error', 500, ['status' => 'error']);
        }
    }

    public function stopTicketRevival(Ticket $ticket)
    {
        $revivalIdToDelete = explode(',',setting('savprocess_stop_revival_ids'));
        foreach ($ticket->threads as $thread) {
            if(in_array($thread->revival_id, $revivalIdToDelete)) {
                $thread->revival_id = null;
                $thread->revival_start_date = null;
                $thread->revival_message_count = 0;
                $thread->save();
            }
        }
    }
}
