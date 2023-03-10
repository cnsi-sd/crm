
#!/usr/bin/php
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/
Artisan::command('testa', function () {
    $message = \App\Models\Ticket\Message::findOrFail(43);
    \App\Events\NewMessage::dispatch($message);
});

Artisan::command('inspire', function () {
    $role = new App\Models\User\Role();
    $role->name = "test";
    $role->save();
    $role1 = \App\Models\User\Role::find(1);

    $user = new App\Models\User\User();
    $user->role_id = $role1->id;
    $user->name = "mathias";
    $user->email = "blabla@blo.fr";
    $user->password = "ksjbfqs";
    $user->active = true;
    $user->save();
    $user1 = \App\Models\User\User::find(1);


    $channel = new App\Models\Channel\Channel();
    $channel->name = "showroomprivee.com";
    $channel->save();
    $channel2 = new App\Models\Channel\Channel();
    $channel2->name = "laposte.fr";
    $channel2->save();
    $channel1 = \App\Models\Channel\Channel::find(1);

    /*$order = new App\Models\Channel\Order();
    $order->channel_id = $channel1->id;
    $order->channel_order_number = "Num commande interne 1";
    $order->save();
    $order1 = \App\Models\Channel\Order::find(1);

    $ticket = new App\Models\Ticket\Ticket();
    $ticket->channel_id = $channel1->id;
    $ticket->order_id = $order1->id;
    $ticket->user_id = $user1->id;
    $ticket->state = "attente";
    $ticket->priority = "P1";
    $ticket->deadline = "2022-11-09";
    $ticket->save();
    $ticket1 = App\Models\Ticket\Ticket::find(1);

    $thread = new \App\Models\Ticket\Thread();
    $thread->ticket_id = $ticket1->id;
    $thread->channel_thread_number = 1;
    $thread->name = "nom du thread";
    $thread->customer_issue = "pb client";
    $thread->save();
    $thread1 = \App\Models\Ticket\Thread::find(1);

    $message = new \App\Models\Ticket\Message();
    $message->thread_id = $thread1->id;
    $message->user_id = $user1->id;
    $message->channel_message_number = 86;
    $message->author_type = "admin";
    $message->content = "sed";
    $message->private = false;
    $message->save();
    $message1 = \App\Models\Ticket\Message::find(1);

    $comment = new \App\Models\Ticket\Comment();
    $comment->thread_id = $thread1->id;
    $comment->user_id = $user1->id;
    $comment->content = "bla bla bla bla bla";
    $comment->displayed = true;
    $comment->type = "Divers";
    $comment->save();
    $comment1 = \App\Models\Ticket\Comment::find(1);

    $thread = $comment1->thread;
    $comment2 = $thread1->comments;

    $thread2 = $ticket1->thread;
    $ticket3 = $thread1->ticket;

    $thread3= $message1->thread;
    $message2 = $thread1->messages;

    $ticket = $thread->ticket;
    $ticket2 = $ticket1->threads;


    //var_dump(getMiraklSetting());

    $attachement = new \App\Models\Ticket\Attachment();
    $attachement->message_id = $message1->id;
    $attachement->filename = "monFichier.php";
    $attachement->save();
    $attachement1 = \App\Models\Ticket\Attachment::find(1);*/


})->purpose('Display an inspiring quote');
