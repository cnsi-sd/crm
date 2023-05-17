<?php

namespace App\Http\Controllers\Configuration;

use App\Helpers\Alert;
use App\Http\Controllers\AbstractController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BotController extends AbstractController
{
    public function home(): View
    {
        return view('configuration.bot.home');
    }

    public function acknowledgement(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            setting(['bot.acknowledgment.active' => $request->has('active')]);
            setting(['bot.acknowledgment.answer_id' => $request->input('answer_id')]);
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
        if ($request->exists('save')) {
            setting(['bot.shipping_information.active' => $request->has('active')]);
            setting(['bot.shipping_information.vir_shipped_answer_id' => $request->input('vir_shipped_answer_id')]);
            setting(['bot.shipping_information.default_shipped_answer_id' => $request->input('default_shipped_answer_id')]);
            setting(['bot.shipping_information.in_preparation_answer_id' => $request->input('in_preparation_answer_id')]);
            setting(['bot.shipping_information.in_preparation_with_delay_answer_id' => $request->input('in_preparation_with_delay_answer_id')]);
            setting(['bot.shipping_information.fulfillment_answer_id' => $request->input('fulfillment_answer_id')]);
            setting(['bot.shipping_information.late_order_tag_id' => $request->input('late_order_tag_id')]);
            setting()->save();

            Alert::toastSuccess(__('app.bot.saved'));
            return redirect()->route('bot_shipping_information');
        }

        return view('configuration.bot.shipping_information');
    }
}
