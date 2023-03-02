<?php

namespace App\Http\Controllers\Tickets;

use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\AbstractController;
use App\Jobs\SendMessage\ConforamaSendMessage;
use App\Jobs\SendMessage\IcozaSendMessage;
use App\Models\Tags\TagList;
use App\Models\Tags\Tags;
use App\Enums\Channel\ChannelEnum;
use App\Jobs\SendMessage\ButSendMessage;
use App\Jobs\SendMessage\CarrefourSendMessage;
use App\Jobs\SendMessage\DartySendMessage;
use App\Jobs\SendMessage\FnacSendMessage;
use App\Jobs\SendMessage\IntermarcheSendMessage;
use App\Jobs\SendMessage\LaposteSendMessage;
use App\Jobs\SendMessage\LeclercSendMessage;
use App\Jobs\SendMessage\MetroSendMessage;
use App\Jobs\SendMessage\RueducommerceSendMessage;
use App\Jobs\SendMessage\ShowroomSendMessage;
use App\Jobs\SendMessage\UbaldiSendMessage;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Thread;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Comment;
use App\Models\Channel\Channel;
use App\Models\User\User;
use App\Enums\Ticket\TicketStateEnum;
use App\Enums\Ticket\TicketPriorityEnum;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use function view;

class TicketController extends AbstractController
{
    public function all_tickets(Request $request): View
    {
        $query = Ticket::query()
            ->select('tickets.*')
            ->join('ticket_threads', 'ticket_threads.ticket_id', 'tickets.id')
            ->leftJoin('tagLists', 'tagLists.thread_id', 'ticket_threads.id')
            ->leftJoin('tag_tagLists', 'tag_tagLists.tagList_id', 'tagLists.id')
            ->leftJoin('tags', 'tags.id', 'tag_tagLists.tag_id')
            ->groupBy('tickets.id');
        $table = (new TableBuilder('all_tickets', $request))
            ->setColumns(Ticket::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        $tickets = $query->get();
        return view('tickets.all_tickets')
            ->with('table', $table)
            ->with('liste', (new \App\Models\Tags\Tag)->getlistTagWithTickets($tickets));
    }

    public function user_tickets(Request $request, ?User $user): View
    {
        $query = Ticket::query()
            ->select(
                'tickets.*')
            ->join('ticket_threads', 'ticket_threads.ticket_id', 'tickets.id')
            ->leftJoin('tagLists', 'tagLists.thread_id', 'ticket_threads.id')
            ->leftJoin('tag_tagLists', 'tag_tagLists.tagList_id', 'tagLists.id')
            ->leftJoin('tags', 'tags.id', 'tag_tagLists.tag_id')
            ->where('tickets.user_id', $user->id)
            ->whereIn('state', [TicketStateEnum::WAITING_ADMIN, TicketStateEnum::WAITING_CUSTOMER])
            ->groupBy('tickets.id');

        $table = (new TableBuilder('user_tickets', $request))
            ->setColumns(Ticket::getTableColumns('user'))
            ->setExportable(false)
            ->setQuery($query);

        $tickets = $query->get();

        return view('tickets.all_tickets')
            ->with('table', $table)
            ->with('liste', (new \App\Models\Tags\Tag)->getlistTagWithTickets($tickets));

    }

    public function redirectOrCreateTicket(Request $request, $channel, $channel_order_number)
    {
        $ticket = null;
        $channel_id = Channel::query()
            ->where('name', 'LIKE', '%'.$channel.'%')->first()->id;

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

    /**
     * @throws \ReflectionException
     */
    public function ticket(Request $request, Ticket $ticket, Thread $thread): View
    {
        $ticket->last_thread_displayed = $thread->id;
        $ticket->save();

        $externalOrderInfo = $this->getExternalOrderInfo($ticket->order->channel_order_number, $ticket->order->channel->name);
        $externalAdditionalOrderInfo = $this->getExternalAdditionalOrderInfo($ticket->order->channel_order_number, $ticket->order->channel->name);
        $externalSuppliers = $this->getExternalSuppliers();

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
                    'author_type' => \App\Enums\Ticket\TicketMessageAuthorTypeEnum::ADMIN,
                    'content' => $request->input('ticket-thread-messages-content'),
                ]);

                match($ticket->channel->name) {
                    ChannelEnum::BUT_FR             => ButSendMessage::dispatch($message),
                    ChannelEnum::CARREFOUR_FR       => CarrefourSendMessage::dispatch($message),
                    ChannelEnum::CONFORAMA_FR       => ConforamaSendMessage::dispatch($message),
                    ChannelEnum::DARTY_COM          => DartySendMessage::dispatch($message),
                    ChannelEnum::INTERMARCHE_FR     => IntermarcheSendMessage::dispatch($message),
                    ChannelEnum::LAPOSTE_FR         => LaposteSendMessage::dispatch($message),
                    ChannelEnum::E_LECLERC          => LeclercSendMessage::dispatch($message),
                    ChannelEnum::METRO_FR           => MetroSendMessage::dispatch($message),
                    ChannelEnum::RUEDUCOMMERCE_FR   => RueducommerceSendMessage::dispatch($message),
                    ChannelEnum::SHOWROOMPRIVE_COM  => ShowroomSendMessage::dispatch($message),
                    ChannelEnum::UBALDI_COM         => UbaldiSendMessage::dispatch($message),
                    ChannelEnum::FNAC_COM           => FnacSendMessage::dispatch($message),
                    ChannelEnum::ICOZA_FR           => IcozaSendMessage::dispatch($message),
                };
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
            ->with('channels', $queryChannels)
            ->with('externalOrderInfo',$externalOrderInfo)
            ->with('externalAdditionalOrderInfo',$externalAdditionalOrderInfo)
            ->with('externalSuppliers',$externalSuppliers);
    }

    public function getExternalOrderInfo($mp_order, $mp_name)
    {
        return Http::get(env('PRESTASHOP_URL') . 'index.php?fc=module&module=bmsmagentogateway&controller=order&mp_order=' . $mp_order . '&mp_name=' . $mp_name)[0];
    }

    public function getExternalAdditionalOrderInfo($mp_order, $mp_name)
    {
        return Http::get(env('PRESTASHOP_URL') . 'index.php?fc=module&module=bmsmagentogateway&controller=order_additional_infos&mp_order=' . $mp_order . '&mp_name=' . $mp_name)->json();
    }

    public function getExternalSuppliers()
    {
        return Http::get(env('PRESTASHOP_URL') . 'index.php?fc=module&module=bmsmagentogateway&controller=supplier')->json();
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
