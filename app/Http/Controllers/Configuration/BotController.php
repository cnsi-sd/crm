<?php

namespace App\Http\Controllers\Configuration;

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
            setting(['autoReply' => $request->input('answer_id')]);
            setting()->save();

            Alert::toastSuccess(__('app.bot.saved'));
            return redirect()->route('bot_acknowledgement');
        }

        return view('configuration.bot.acknowledgement');
    }

    public function invoice(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            setting(['bot.invoice.active' => $request->has('active')]);
            setting(['bot.invoice.found_answer_id' => $request->input('found_answer_id')]);
            setting(['bot.invoice.not_shipped_answer_id' => $request->input('not_shipped_answer_id')]);
            setting()->save();

            Alert::toastSuccess(__('app.bot.saved'));
            return redirect()->route('bot_invoice');
        }

        return view('configuration.bot.invoice');
    }

    public function shipping_information(Request $request): View|RedirectResponse
    {
        return view('configuration.bot.home');
    }
}
