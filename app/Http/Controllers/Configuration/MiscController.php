<?php

namespace App\Http\Controllers\Configuration;

use App\Enums\Ticket\MessageVariable;
use App\Helpers\Alert;
use App\Http\Controllers\AbstractController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MiscController extends AbstractController
{
    public function home(): View
    {
        return view('configuration.misc.home');
    }

    public function variables(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            foreach (MessageVariable::cases() as $variable) {
                if(!$variable->isConfigurable())
                    continue;

                $key = $variable->getSettingKey();
                $request_key = str_replace('.', '_', $key);

                if ($request->exists($request_key)) {
                    $variable->saveValue($request->input($request_key));
                }
            }

            Alert::toastSuccess(__('app.config.misc.saved'));
            return redirect()->back();
        }

        return view('configuration.misc.variables');
    }

    public function incidents(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            setting(['incident_tag_id' => $request->input('incident_tag_id')]);
            setting()->save();

            Alert::toastSuccess(__('app.config.misc.saved'));
            return redirect()->back();
        }

        return view('configuration.misc.incidents');
    }
}