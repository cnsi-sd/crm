<?php

namespace App\Http\Controllers\Configuration;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Helpers\TinyMCE;
use App\Http\Controllers\AbstractController;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use function view;

class DefaultAnswerController extends AbstractController
{
    public function list(Request $request): View
    {
        $query = DefaultAnswer::query();
        $table = (new TableBuilder('defaultAnswers', $request))
            ->setColumns(DefaultAnswer::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('configuration.defaultAnswer.list')
            ->with('table', $table);
    }

    public function index()
    {
        $defaultAnswer = DefaultAnswer::all();

        return view('configuration.defaultAnswer.list', ['allAnswer' => $defaultAnswer]);
    }

    public function create()
    {
        return view('configuration.defaultAnswer.create');
    }

    public function store(Request $request)
    {
        DefaultAnswer::create([
            'name' => $request->get('name'),
            'content' => $request->get('content'),
        ]);
        return redirect('/configuration/default_response');
    }

    public function edit(Request $request, ?DefaultAnswer $defaultAnswer)
    {
        if (!$defaultAnswer)
            $defaultAnswer = new DefaultAnswer();

        if ($request->exists('save_default_answer'))
        {
            $this->save_default_answer($request, $defaultAnswer);
            alert::toastSuccess(__('app.default_answer.save'));
            return redirect()->route('default_answers');
        }

        return view('configuration.defaultAnswer.edit')
            ->with('defaultAnswer', $defaultAnswer);
    }

    public function save_default_answer(Request $request, DefaultAnswer $defaultAnswer)
    {
        // Validate request
        $request->validate([
            'name' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        // Set name, content
        $defaultAnswer->name = $request->input('name');
        $defaultAnswer->content = TinyMCE::toText($request->input('content'));
        $defaultAnswer->is_locked = $request->input('is_locked') == "on";

        // Save
        $defaultAnswer->save();

        $channels = $request->input('channels');
        $defaultAnswer->channels()->sync($channels);
    }

    public function delete(Request $request, ?DefaultAnswer $defaultAnswer)
    {
        if($defaultAnswer->softDeleted()){
            alert::toastSuccess(__('app.default_answer.deleted'));
        }
        return redirect()->route('default_answers');
    }

}
