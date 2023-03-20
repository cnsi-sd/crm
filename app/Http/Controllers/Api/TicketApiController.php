<?php

namespace App\Http\Controllers\Api;

use App\Enums\Ticket\TicketStateEnum;
use App\Models\Tags\Tag;
use App\Models\Ticket\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketApiController extends AbstractApiController
{
    public function savProcessComplete(Request $request, string $token, Ticket $ticket) : JsonResponse
    {
        if($token === env('SAVPROCESS_CRM_TOKEN')) {
            $ticket->deadline = date('Y-m-d');
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            //TODO $tagId = setting('savprocess.processcomplete.tag_id');
            $tagId = env('SAVPROCESS_CRM_PROCESSCOMPLETE_TAG_ID');
            $tag = Tag::findOrFail($tagId);
            $ticket->addTag($tag);
            $this->stopTicketRevival($ticket);
            $ticket->save();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
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
