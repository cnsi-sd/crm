<?php

namespace App\Http\Controllers\Configuration;

use App\Enums\Ticket\MessageVariable;
use App\Helpers\Alert;
use App\Http\Controllers\AbstractController;
use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Channel\DefaultAnswer;
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

    public function savprocess(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            setting(['savprocesscomplete_tag_id' => $request->input('savprocesscomplete_tag_id')]);
            setting(['savprocess_stop_revival_ids' => implode(',',$request->input('savprocess_stop_revival_ids'))]);
            setting()->save();

            Alert::toastSuccess(__('app.config.misc.saved'));
            return redirect()->back();
        }
        return view('configuration.misc.savprocess');
    }

    public function closedDiscussion(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            setting(['closed_discussion_tag_id' => $request->input('closed_discussion_tag_id')]);
            setting()->save();

            Alert::toastSuccess(__('app.config.misc.saved'));
            return redirect()->back();
        }

        return view('configuration.misc.closedDiscussion');
    }

    public function miraklRefunds(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            setting(['mirakl_refunds_tag_id' => $request->input('mirakl_refunds_tag_id')]);
            setting()->save();

            Alert::toastSuccess(__('app.config.misc.saved'));
            return redirect()->back();
        }

        return view('configuration.misc.mirakl_refunds');
    }

    public function answerOfferQuestions(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            setting(['default_answer_offer_questions' => $request->input('default_answer_offer_questions')]);
                        setting()->save();

            Alert::toastSuccess(__('app.config.misc.saved'));
            return redirect()->back();
        }

        return view('configuration.misc.answer_offer_questions');
    }

    public function parcelManagement(Request $request): View|RedirectResponse
    {
        if ($request->exists('save')) {
            // Output config
            setting(['pm.active' => $request->input('active') === 'on']);
            setting(['pm.app_url' => $request->input('app_url')]);
            setting(['pm.api_url' => $request->input('api_url')]);
            setting(['pm.api_token' => $request->input('api_token')]);
            setting(['pm.id_shop' => $request->input('id_shop')]);

            // Input config
            setting(['pm.accepted_return_tag' => $request->input('accepted_return_tag')]);
            setting(['pm.refused_return_tag' => $request->input('refused_return_tag')]);
            setting(['pm.return_with_reserves_tag' => $request->input('return_with_reserves_tag')]);
            setting(['pm.return_with_remark_tag' => $request->input('return_with_remark_tag')]);
            setting(['pm.close_api_token' => $request->input('close_api_token')]);
            setting()->save();

            Alert::toastSuccess(__('app.config.misc.saved'));
            return redirect()->back();
        }

        return view('configuration.misc.parcel_management');
    }
}
