<?php

namespace App\Http\Controllers\Tickets;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Channel\MirakleChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketPriorityEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Helpers\Prestashop\LiveoGateway;
use App\Helpers\TinyMCE;
use App\Http\Controllers\AbstractController;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Tags\TagList;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use App\Models\User\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Cnsi\Attachments\Model\Document;

class TicketController extends AbstractController
{
    public function all_tickets(Request $request): View
    {
        $query = Ticket::query()
            ->select('tickets.*')
            ->join('ticket_threads', 'ticket_threads.ticket_id', '=','tickets.id')
            ->leftJoin('tagLists', 'tagLists.ticket_id', '=', 'tickets.id')
            ->leftJoin('tag_tagLists', 'tag_tagLists.taglist_id', '=', 'tagLists.id')
            ->leftJoin('tags', 'tag_tagLists.tag_id', '=', 'tags.id')
            ->groupBy('tickets.id');
        $table = (new TableBuilder('all_tickets', $request))
            ->setColumns(Ticket::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        $tickets = $table->getQueryBeforePagination()->get();

        return view('tickets.all_tickets')
            ->with('table', $table)
            ->with('listTags', Tag::getListTagWithTickets($tickets));
    }

    public function user_tickets(Request $request, ?User $user): View
    {
        $query = Ticket::query()
            ->select('tickets.*')
            ->join('ticket_threads', 'ticket_threads.ticket_id', 'tickets.id')
            ->leftJoin('tagLists', 'tagLists.ticket_id', '=', 'tickets.id')
            ->leftJoin('tag_tagLists', 'tag_tagLists.taglist_id', '=', 'tagLists.id')
            ->leftJoin('tags', 'tag_tagLists.tag_id', '=', 'tags.id')
            ->where('user_id', $user->id)
            ->where('state', TicketStateEnum::OPENED)
            ->groupBy('tickets.id');

        $table = (new TableBuilder('user_tickets', $request))
            ->setColumns(Ticket::getTableColumns('user'))
            ->setExportable(false)
            ->setQuery($query);

        $tickets = $table->getQueryBeforePagination()->get();

        return view('tickets.all_tickets')
            ->with('table', $table)
            ->with('listTags', Tag::getListTagWithTickets($tickets));

    }

    public function redirectOrCreateTicket(Request $request, $channel, $channel_order_number): RedirectResponse
    {
        // Get Channel
        $channel = Channel::getByExtName($channel);
        if (!$channel) {
            Alert::modalError('Canal inconnu.');
            return redirect()->route('home');
        }

        // Get order & ticket
        $order = Order::getOrder($channel_order_number, $channel);
        $ticket = Ticket::getTicket($order, $channel, true);

        // Redirect to ticket
        return redirect()->route('ticket', $ticket);
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

    public function post_comment(Request $request, Ticket $ticket): View
    {
        if($request->input('content')) {
            $request->validate([
                'content'     => ['required','string'],
                'type'         => ['required','string'],
            ]);
            $comment = Comment::firstOrCreate([
                'ticket_id' => $ticket->id,
                'user_id' => $request->user()->id,
                'content' => $request->input('content'),
                'displayed' => 1,
                'type' => $request->input('type'),
            ]);
        }
        return view('tickets.parts.private_comment')
            ->with('comment', $comment);
    }

    public function get_external_infos(Ticket $ticket): View
    {
        $externalOrderInfo = $ticket->order->getPrestashopOrders();
        $externalOrderLink = $ticket->order->getPrestashopExternalLink();
        $externalInvoiceLink = $ticket->order->getInvoiceExternalLink();
        return view('tickets.parts.external_order_info')
            ->with('orders', $externalOrderInfo)
            ->with('external_link',$externalOrderLink)
            ->with('external_invoice_link', $externalInvoiceLink);
    }

    public function save_revivalThread(Request $request){
        $thread = Thread::find($request->input('thread_id'));
        $thread->revival_id = $request->input('revival_id');
        $thread->revival_start_date = $request->input('delivery_date') . ' 09:00:00';
        $thread->save();

        return view('tickets.parts.revival')
            ->with('thread', $thread);
    }

    /**
     * @throws \ReflectionException
     */
    public function ticket(Request $request, Ticket $ticket, Thread $thread): View|RedirectResponse
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
                'ticket-delivery_date' => ['nullable', 'date'],
            ]);
            $ticket->state = $request->input('ticket-state');
            $ticket->priority = $request->input('ticket-priority');
            $ticket->user_id = $request->input('ticket-user_id');
            $ticket->deadline = $request->input('ticket-deadline');
            $ticket->direct_customer_email = $request->input('ticket-customer_email');
            $ticket->customer_issue = $request->input('ticket-customer_issue');
            $ticket->delivery_date = $request->input('ticket-delivery_date');
            $ticket->save();

            if($messageContent = $request->input('ticket-thread-messages-content')) {
                $request->validate([
                    'ticket-thread-messages-content'     => ['required','string'],
                ]);
                $defaultAnswerId = $request->input('default_answer_select');
                $message = Message::firstOrCreate([
                    'thread_id' => $thread->id,
                    'user_id' => $request->user()->id,
                    'author_type' => TicketMessageAuthorTypeEnum::ADMIN,
                    'content' => TinyMCE::toText($messageContent),
                    'default_answer_id' => $defaultAnswerId,
                ]);

                foreach ($request->files as $name => $file)
                {
                    $attachmentIndex = explode("_file_", $name)[1];
                    $this->uploadFile($request, $message, "attachment_type_".$attachmentIndex, $name);
                }

                AbstractSendMessage::dispatchMessage($message);
            }
            Alert::toastSuccess(__('app.ticket.saved'));
            return redirect()->back();
        }

        if($thread->ticket->id !== $ticket->id)
            abort(404);

        $othersChannels = Channel::all()->except($ticket->channel->id);
        $othersChannelsNames = [];
        foreach($othersChannels as $otherChannel) {
            foreach ($otherChannel->ext_names as $ext_name) {
                $othersChannelsNames[] = $ext_name;
            }
        }

        return view('tickets.ticket')
            ->with('ticket', $ticket)
            ->with('thread', $thread)
            ->with('othersChannelsNames', $othersChannelsNames)
            ->with('documents_table', $ticket->getDocumentsTable($request, route('upload_document', [$ticket, $ticket::class])));
    }

    public function delete_tag(Request $request) {
        $tag = Tag::find($request->input('tag_id'));
        $ticket = Ticket::find($request->input('ticket_id'));
        $tag->taglists()->detach($request->input('taglist_id'));

        $taglist = TagList::find($request->input('taglist_id'));
        if ($taglist->tags()->count() == 0)
            $taglist->delete();
        return view('tickets.parts.tags')
            ->with('ticket', $ticket);
    }

    public function saveTicketTags(Request $request) {
        $ticket = Ticket::find($request->input('ticket_id'));
        $taglist = TagList::find($request->input('taglist_id'));
        $tag = Tag::findOrFail($request->input('tag_id'));

        if($taglist == null){
            $taglist = new TagList();
            $taglist->ticket_id = $ticket->id;
            $taglist->save();
        }

        $taglist->addTag($tag);
        return view('tickets.parts.tags')
            ->with('ticket', $ticket);
    }

    public function hasTagList(Request $request){
        $ticket = Ticket::find($request->input('ticket_id'));
        return $ticket->taglists()->count();
    }

    public function clickAndCall(Request $request): JsonResponse
    {
        try {
            LiveoGateway::startCall(auth()->user()->email, $request->input('phone_number'));
            return response()->json([
                'status' => 'success',
                'message' => __('app.ticket.click_and_call.success'),
            ]);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function uploadFile($request, $message, $typeName, $fileName)
    {
        $modelLoad = App::make($message::class);
        Document::upload(
            $request,
            $modelLoad->find($message->id),
            $typeName,
            $fileName
        );
    }

}
