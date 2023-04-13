<?php

namespace App\Http\Controllers\Api;

use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Enums\Ticket\TicketPriorityEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Tags\Tag;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParcelManagementApiController extends AbstractApiController
{
    protected $logger_channel = 'api/in/ticket/parcel_management/parcel_management.log';

    public function notify(Request $request, Ticket $ticket, string $token, string $comment, string $tag): JsonResponse
    {
        try {
            $headers = ['Access-Control-Allow-Origin' => '*'];
            $this->logger->info('START notify on ticket ' . $ticket->id);

            // Check if PM is active
            if (!setting('pm.active')) {
                $this->logger->error('Parcel Management Inactive');
                return $this->message('Parcel Management Inactive', 403, ['status' => 'error'], $headers);
            }

            // Check token
            if ($token !== setting('pm.close_api_token')) {
                $this->logger->error('Invalid token, got `' . $token . '`');
                return $this->message('Invalid token', 401, ['status' => 'error'], $headers);
            }

            // Create comment
            $dbComment = new Comment();
            $dbComment->ticket_id = $ticket->id;
            $dbComment->content = $comment;
            $dbComment->displayed = true;
            $dbComment->type = TicketCommentTypeEnum::OTHERS;
            $dbComment->save();

            // Update ticket deadline and state
            $ticket->priority = TicketPriorityEnum::P2;
            $ticket->deadline = Ticket::getAutoDeadline();
            $ticket->state = TicketStateEnum::OPENED;
            $ticket->save();

            // Stop running revivals
            foreach ($ticket->threads as $thread)
                $thread->stopRevival();

            // Add tag to ticket
            $tagSetting = $tag . '_tag';
            $tagId = setting('pm.' . $tagSetting);
            $tag = Tag::findOrFail($tagId);
            if (!$ticket->hasTag($tag))
                $ticket->addTag($tag);

            $this->logger->info('--- DONE ---');
            return $this->message('Ticket Updated', 200, ['status' => 'success'], $headers);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            return $this->message('Internal Server Error', 500, ['status' => 'error']);
        }
    }

    public function has_been_notified(Request $request, string $token, Ticket $ticket, string $comment): JsonResponse
    {
        try {
            $headers = ['Access-Control-Allow-Origin' => '*'];
            $this->logger->info('START has been notified ticket ' . $ticket->id);

            // Check if PM is active
            if (!setting('pm.active')) {
                $this->logger->error('Parcel Management Inactive');
                return $this->message('Parcel Management Inactive', 403, ['status' => 'error'], $headers);
            }

            // Check token
            if ($token !== setting('pm.close_api_token')) {
                $this->logger->error('Invalid token, got `' . $token . '`');
                return $this->message('Invalid token', 401, ['status' => 'error'], $headers);
            }

            $isNotified = false;
            foreach($ticket->comments as $dbComment) {
                if($dbComment->content === $comment) {
                    $isNotified = true;
                    break;
                }
            }

            $this->logger->info('--- DONE ---');
            return response()->json(['status' => 'success', 'isNotified' => $isNotified], 200, $headers);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            return $this->message('Internal Server Error', 500, ['status' => 'error']);
        }
    }
}
