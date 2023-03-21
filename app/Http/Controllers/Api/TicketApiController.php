<?php

namespace App\Http\Controllers\Api;

use App\Enums\Ticket\TicketStateEnum;
use App\Models\Tags\Tag;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class TicketApiController
{
    protected Logger $logger;
    public function savProcessComplete(Request $request, string $token, Ticket $ticket) : JsonResponse
    {
        try {
            $this->logger = new Logger('api/in/ticket/savProcessComplete.log', true, true);
            $this->logger->info('--- START : updating ticket ' . $ticket->id . ' after sav process completed by customer ---');
            if($token === env('SAVPROCESS_CRM_TOKEN')) {
                $ticket->deadline = date('Y-m-d');
                $ticket->state = TicketStateEnum::WAITING_ADMIN;
                //TODO $tagId = setting('savprocess.processcomplete.tag_id');
                $tagId = env('SAVPROCESS_CRM_PROCESSCOMPLETE_TAG_ID');
                $tag = Tag::findOrFail($tagId);
                $ticket->addTag($tag);
                $this->stopTicketRevival($ticket);
                $ticket->save();
                $this->logger->info('--- DONE ---');
                return response()->json(['status' => 'success']);
            } else {
                $this->logger->info('--- TOKEN NOT DEFINED ---');
                return response()->json(['status' => 'error', 'error' => 'Token not defined']);
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            return response()->json(['status' => 'error', 'error' => $e]);
        }
    }

    public function stopTicketRevival(Ticket $ticket)
    {
        //TODO $revivalIdToDelete = setting('savprocess.delete_revival.revival_ids');
        $revivalIdToDelete = explode(",",env('SAVPROCESS_CRM_DELETE_REVIVAL_IDS'));
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
