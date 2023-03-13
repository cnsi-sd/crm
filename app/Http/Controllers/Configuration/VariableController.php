<?php

namespace App\Http\Controllers\Configuration;

use App\Enums\Ticket\MessageVariable;
use App\Helpers\Alert;
use App\Http\Controllers\AbstractController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VariableController extends AbstractController
{
    public function config(Request $request): View|RedirectResponse
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

            Alert::toastSuccess(__('app.variable.saved'));
            return redirect()->route('variables_config');
        }

        return view('configuration.variable.config');
    }
}
