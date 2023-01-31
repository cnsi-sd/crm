<?php

namespace App\Http\Controllers\Tickets;

use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Controller;
use App\Models\Ticket\Revival\Revival;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Thread;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Comment;
use App\Models\Channel\Channel;
use App\Models\User\User;
use App\Enums\Ticket\TicketStateEnum;
use App\Enums\Ticket\TicketPriorityEnum;
use http\Env\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use function view;

class TicketController extends Controller
{
    public function all_tickets(Request $request): View
    {
        $query = Ticket::query();
        $table = (new TableBuilder('all_tickets', $request))
            ->setColumns(Ticket::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('tickets.all_tickets')
            ->with('table', $table);
    }

    public function user_tickets(Request $request, ?User $user): View
    {
        $query = Ticket::query()
            ->where('user_id', $user->id)
            ->whereIn('state', [TicketStateEnum::WAITING_ADMIN, TicketStateEnum::WAITING_CUSTOMER]);

        $table = (new TableBuilder('user_tickets', $request))
            ->setColumns(Ticket::getTableColumns('user'))
            ->setExportable(false)
            ->setQuery($query);

        return view('tickets.all_tickets')
            ->with('table', $table);
    }

    public function redirectTicket(Request $request, ?Ticket $ticket)
    {
        if ($ticket->last_thread_displayed) {
            $threadId = $ticket->last_thread_displayed;
        } else {
            $threadId = $ticket->threads->first()->id;
        }
        return redirect()->route('ticket_thread', [$ticket,$threadId]);
    }

    public function toggle_comment(Comment $comment): \Illuminate\Http\JsonResponse
    {
        $comment->displayed = !$comment->displayed;
        $comment->save();
        return response()->json(['message' => 'success']);
    }

    public function customer_service_process(Request $request, Ticket $ticket): View
    {
        return view('tickets.customer_service_process')->with('ticket',$ticket);
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
                'ticket-customer_email' => ['string'],
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
                Message::firstOrCreate([
                    'thread_id' => $thread->id,
                    'user_id' => $request->user()->id,
                    'author_type' => \App\Enums\Ticket\TicketMessageAuthorTypeEnum::ADMIN,
                    'content' => $request->input('ticket-thread-messages-content'),
                ]);
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

        $checkThreadTicket = Thread::query()
            ->where('id', $thread->id)
            ->where('ticket_id', $ticket->id)
            ->first();

        if(!$checkThreadTicket) {
            abort(404);
        }

        $queryOrder = Order::query()->where('id', $ticket->order_id)->first();
        $queryUsers = User::all();
        $queryThreads = Thread::query()->where('ticket_id', $ticket->id)->get();
        $queryMessages = Message::query()->where('thread_id', $thread->id)->orderBy('created_at', "DESC")->get();
        $queryComments = Comment::query()->where('thread_id', $thread->id)->orderBy('created_at', "DESC")->get();
        $queryChannels = Channel::query()->get();

        return view('tickets.ticket')
            ->with('ticket',$ticket)
            ->with('thread',$thread)
            ->with('order',$queryOrder)
            ->with('users', $queryUsers)
            ->with('threads', $queryThreads)
            ->with('messages', $queryMessages)
            ->with('comments', $queryComments)
            ->with('commentTypeEnum', TicketCommentTypeEnum::getList())
            ->with('ticketStateEnum', TicketStateEnum::getList())
            ->with('ticketPriorityEnum', TicketPriorityEnum::getList())
            ->with('channels', $queryChannels);
    }

}
