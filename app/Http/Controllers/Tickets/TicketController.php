<?php

namespace App\Http\Controllers\Tickets;

use App\Enums\Channel\ChannelEnum;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Controller;
use App\Jobs\SendMessage\ButSendMessage;
use App\Jobs\SendMessage\CarrefourSendMessage;
use App\Jobs\SendMessage\ConforamaSendMesssage;
use App\Jobs\SendMessage\DartySendMessage;
use App\Jobs\SendMessage\FnacSendMessage;
use App\Jobs\SendMessage\IntermarcheSendMessage;
use App\Jobs\SendMessage\LaposteSendMessage;
use App\Jobs\SendMessage\LeclercSendMessage;
use App\Jobs\SendMessage\MetroSendMessage;
use App\Jobs\SendMessage\RueDuCommerceSendMessage;
use App\Jobs\SendMessage\ShowroomSendMessage;
use App\Jobs\SendMessage\UbaldiSendMessage;
use App\Models\Ticket\Revival\Revival;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Message;
use App\Models\Ticket\Comment;
use App\Models\Channel\Channel;
use App\Models\User\User;
use App\Enums\Ticket\TicketStateEnum;
use App\Enums\Ticket\TicketPriorityEnum;
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
        //todo : utiliser last_thread_displayed pour afficher le dernier thread chargé
        $queryThreads = Thread::query()->where('ticket_id', $ticket->id)->first()->toArray();
        return redirect()->route('ticket_thread', [$ticket,$queryThreads['id']]);
    }

    /**
     * @throws \ReflectionException
     */
    public function ticket(Request $request, ?Ticket $ticket, ?Thread $thread): View
    {
        if ($request->input()){
            //$request->validate();
            $ticket->state = $request->input('ticket-state');
            $ticket->priority = $request->input('ticket-priority');
            $ticket->user_id = $request->input('ticket-user_id');
            $ticket->deadline = $request->input('ticket-deadline');
            $ticket->channel_id = $request->input('ticket-channel');
            $ticket->order_id = $request->input('ticket-order_id');
            $ticket->direct_customer_email = $request->input('ticket-customer_email');
            $ticket->delivery_date = $request->input('ticket-delivery_date');
            $ticket->save();


            $thread->customer_issue = $request->input('ticket-thread-customer_issue');
            $thread->revival_id = $request->input('ticket-revival');
            $thread->revival_start_date = $request->input('revival-delivery_date') . ' 09:00:00';
            $thread->save();

            if($request->input('ticket-thread-messages-content')) {
                $message = Message::firstOrCreate([
                    'thread_id' => $thread->id,
                    'user_id' => $request->user()->id,
                    'author_type' => 'admin',
                    'content' => $request->input('ticket-thread-messages-content'),
                ]);

                //TODO dispatch job
                match($ticket->channel->name) {
                    ChannelEnum::BUT_FR             => ButSendMessage::dispatch($message),
                    ChannelEnum::CARREFOUR_FR       => CarrefourSendMessage::dispatch($message),
                    ChannelEnum::CONFORAMA_FR       => ConforamaSendMesssage::dispatch($message),
                    ChannelEnum::DARTY_COM          => DartySendMessage::dispatch($message),
                    ChannelEnum::INTERMARCHE_FR     => IntermarcheSendMessage::dispatch($message),
                    ChannelEnum::LAPOSTE_FR         => LaposteSendMessage::dispatch($message),
                    ChannelEnum::E_LECLERC          => LeclercSendMessage::dispatch($message),
                    ChannelEnum::METRO_FR           => MetroSendMessage::dispatch($message),
                    ChannelEnum::RUEDUCOMMERCE_FR   => RueDuCommerceSendMessage::dispatch($message),
                    ChannelEnum::SHOWROOMPRIVE_COM  => ShowroomSendMessage::dispatch($message),
                    ChannelEnum::UBALDI_COM         => UbaldiSendMessage::dispatch($message),
                    ChannelEnum::FNAC_COM           => FnacSendMessage::dispatch($message)
                };
            }
            if($request->input('ticket-thread-comments-content')) {
                Comment::firstOrCreate([
                    'thread_id' => $thread->id,
                    'user_id' => $request->user()->id,
                    'content' => $request->input('ticket-thread-comments-content'),
                    'displayed' => 1,
                    'type' => 'responsible_info',
                ]);
            }
        }

        $queryTicket = Ticket::query()
            ->where('id', $ticket->id)
            ->first()
            ->toArray();
        $queryThread = Thread::query()
            ->where('id', $thread->id)
            ->first()
            ->toArray();

        $queryUsers = User::query()->get()->toArray();

        $queryThreads = Thread::query()->where('ticket_id', $ticket->id)->get()->toArray();
        $threads = [];
        foreach ($queryThreads as $thread2) {
            $threads[] = $thread2['id'];
        }
        $queryMessages = Message::query()->where('thread_id', $queryThread['id'])->orderBy('created_at', "DESC")->get()->toArray();
        $queryComments = Comment::query()->where('thread_id', $queryThread['id'])->orderBy('created_at', "DESC")->get()->toArray();
        $queryChannels = Channel::query()->get()->toArray();



        return view('tickets.ticket')
            ->with('ticket',$queryTicket)
            ->with('thread', $thread)
            ->with('activeThread',$queryThread)
            ->with('users', $queryUsers)
            ->with('threads', $queryThreads)
            ->with('messages', $queryMessages)
            ->with('comments', $queryComments)
            ->with('ticketStateEnum', TicketStateEnum::getList())
            ->with('ticketPriorityEnum', TicketPriorityEnum::getList())
            ->with('channels', $queryChannels);
    }

}
