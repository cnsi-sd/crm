<?php

namespace App\Http\Controllers\Configuration;

use Akaunting\Setting;
use App\Helpers\Alert;
use App\Http\Controllers\AbstractController;
use Illuminate\Http\Request;

class AutoReplyController extends AbstractController
{

    public function edit(Request $request){
        $r = $request;
        if($request->exists('auto_reply')) {
            $this->saveSetting($request);
            alert::toastSuccess(__('app.defaultAnswer.save'));
            return redirect()->route('autoReply');
        }
        return view('configuration.autoReply.autoReply');
    }

    private function saveSetting(Request $request)
    {
        setting(['autoReplyActivate' => $request->has('autoReplyActivate')]);
        setting(['autoReply' => $request->input('reply')]);
        setting()->save();
    }
}
