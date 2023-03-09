<?php

namespace App\Http\Controllers\Tickets;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Helpers\PrestashopGateway;
use App\Http\Controllers\AbstractController;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Tags\Tag;
use App\Models\Tags\TagList;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Thread;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Comment;
use App\Models\Channel\Channel;
use App\Models\User\User;
use App\Enums\Ticket\TicketStateEnum;
use App\Enums\Ticket\TicketPriorityEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends AbstractController
{
    public function all_tickets(Request $request): View
    {
        $query = Ticket::query()
            ->select('tickets.*')
            ->join('ticket_threads', 'ticket_threads.ticket_id', '=','tickets.id')
            ->groupBy('tickets.id');
        $table = (new TableBuilder('all_tickets', $request))
            ->setColumns(Ticket::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        $tickets = $query->get();

        return view('tickets.all_tickets')
            ->with('table', $table)
            ->with('listTags', (new \App\Models\Tags\Tag)->getlistTagWithTickets($tickets));
    }

    public function user_tickets(Request $request, ?User $user): View
    {
        $query = Ticket::query()
            ->select('tickets.*')
            ->join('ticket_threads', 'ticket_threads.ticket_id', 'tickets.id')
            ->where('user_id', $user->id)
            ->whereIn('state', [TicketStateEnum::WAITING_ADMIN, TicketStateEnum::WAITING_CUSTOMER])
            ->groupBy('tickets.id');

        $table = (new TableBuilder('user_tickets', $request))
            ->setColumns(Ticket::getTableColumns('user'))
            ->setExportable(false)
            ->setQuery($query);

        $tickets = $query->get();

        return view('tickets.all_tickets')
            ->with('table', $table)
            ->with('listTags', (new \App\Models\Tags\Tag)->getlistTagWithTickets($tickets));;

    }

    public function redirectOrCreateTicket(Request $request, $channel, $channel_order_number)
    {
        $ticket = null;
        $channel_id = Channel::query()
            ->where('ext_name', $channel)->first()->id;

        if ($channel_id) {
            $order = Order::query()
                ->where('channel_id', $channel_id)
                ->where('channel_order_number', $channel_order_number)
                ->first();
            if ($order) {
                $ticket = Ticket::query()
                    ->where('order_id', $order->id)->first();
            }
        }

        if(!$ticket){
            $order = new Order;
            $order->channel_id = $channel_id;
            $order->channel_order_number = $channel_order_number;
            $order->save();

            $ticket = new Ticket();
            $ticket->channel_id = $channel_id;
            $ticket->order_id = $order->id;
            $ticket->user_id = Channel::query()->where('id', $order->channel_id)->first()->user_id;
            $ticket->state  = TicketStateEnum::WAITING_ADMIN;
            $ticket->priority = TicketPriorityEnum::P1;
            $ticket->deadline = new \DateTime('now');
            $ticket->save();

            $thread = new Thread();
            $thread->ticket_id = $ticket->id;
            $thread->name = "Fil de discussion principal";
            $thread->save();
        }

        return redirect()->route('ticket', [$ticket]);
    }

    public function redirectTicket(Request $request, ?Ticket $ticket): RedirectResponse
    {
        if ($ticket->last_thread_displayed) {
            $threadId = $ticket->last_thread_displayed;
        } else {
            $threadId = $ticket->threads->first()->id;
        }
        return redirect()->route('ticket_thread', [$ticket,$threadId]);
    }

    public function toggle_comment(Comment $comment): JsonResponse
    {
        $comment->displayed = !$comment->displayed;
        $comment->save();
        return response()->json(['message' => 'success']);
    }

    public function get_external_infos(Ticket $ticket): View
    {
        $externalOrderInfo = $ticket->order->getPrestashopOrders();
        return view('tickets.parts.external_order_info')
            ->with('orders', $externalOrderInfo);
    }

    /**
     * @throws \ReflectionException
     */
    public function ticket(Request $request, Ticket $ticket, Thread $thread): View
    {
        $ticket->last_thread_displayed = $thread->id;
        $ticket->save();

        if ($request->input()){
            $request->validate([
                'ticket-state'     => ['required','string'],
                'ticket-priority'  => ['required','string'],
                'ticket-user_id'   => ['required','integer', 'exists:App\Models\User\User,id'],
                'ticket-deadline'  => ['required','date'],
                'ticket-customer_email' => ['nullable','string'],
                'ticket-delivery_date' => ['date']
            ]);
            $ticket->state = $request->input('ticket-state');
            $ticket->priority = $request->input('ticket-priority');
            $ticket->user_id = $request->input('ticket-user_id');
            $ticket->deadline = $request->input('ticket-deadline');
            $ticket->direct_customer_email = $request->input('ticket-customer_email');
            $ticket->delivery_date = $request->input('ticket-delivery_date');
            $ticket->save();

            $thread->customer_issue = $request->input('ticket-thread-customer_issue');
            $thread->revival_id = $request->input('ticket-revival');
            $thread->revival_start_date = $request->input('revival-delivery_date') . ' 09:00:00';
            $thread->save();

            if($request->input('ticket-thread-messages-content')) {
                $request->validate([
                    'ticket-thread-messages-content'     => ['required','string'],
                ]);
                $message = Message::firstOrCreate([
                    'thread_id' => $thread->id,
                    'user_id' => $request->user()->id,
                    'author_type' => TicketMessageAuthorTypeEnum::ADMIN,
                    'content' => $request->input('ticket-thread-messages-content'),
                ]);

                AbstractSendMessage::dispatchMessage($message);
            }
            if($request->input('ticket-thread-comments-content')) {
                $request->validate([
                    'ticket-thread-comments-content'     => ['required','string'],
                ]);
                Comment::firstOrCreate([
                    'thread_id' => $thread->id,
                    'user_id' => $request->user()->id,
                    'content' => $request->input('ticket-thread-comments-content'),
                    'displayed' => 1,
                    'type' => $request->input('ticket-thread-comments-type'),
                ]);
            }
            Alert::toastSuccess(__('app.ticket.saved'));
        }

        if($thread->ticket->id !== $ticket->id)
            abort(404);

        return view('tickets.ticket')
            ->with('ticket', $ticket)
            ->with('thread', $thread);
    }

    public function delete_tag(Request $request) {
        $tag = Tag::find($request->input('tag_id'));
        $tag->taglists()->detach($request->input('taglist_id'));
        return redirect()->route('all_tickets');
    }

    public function delete_ThreadTagList(Request $request) {
        $taglist = TagList::find($request->input('taglist_id'));
        $taglist->tags()->detach();
        $taglist->delete();
    }

    public function saveThreadTags(Request $request) {
        $taglist = TagList::find($request->input('taglist_id'));
        $tag = Tag::find($request->input('tag_id'));
        $tag->taglists()->attach($taglist->id);
        return response()->json($tag);
    }

}
