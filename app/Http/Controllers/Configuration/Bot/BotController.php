<?php

namespace App\Http\Controllers\Configuration\Bot;

use App\Helpers\Alert;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BotController
{
    public function home(): View
    {
        return view('configuration.bot.home');
    }

    public function acknowledgement(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            setting(['autoReplyActivate' => $request->has('active')]);
            setting(['autoReply' => $request->input('default_answer_id')]);
            setting()->save();

            Alert::toastSuccess(__('app.bot.saved'));
            return redirect()->route('bot_acknowledgement');
        }

        return view('configuration.bot.acknowledgement');
    }

    public function invoice(Request $request): View|RedirectResponse
    {
        return view('configuration.bot.home');
    }

    public function shipping_information(Request $request): View|RedirectResponse
    {
        return view('configuration.bot.home');
    }
}
