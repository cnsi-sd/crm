<?php

namespace App\Http\Controllers\Configuration;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\Controller;
use App\Models\Channel\DefaultAnswers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use function view;

class DefaultAnswerController extends Controller
{
    public function list(Request $request): View
    {
        $query = DefaultAnswers::query();
        $table = (new TableBuilder('defaultAnswers', $request))
            ->setColumns(DefaultAnswers::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('configuration.defaultAnswer.list')
            ->with('table', $table);
    }

    public function index()
    {
        $defaultAnswer = DefaultAnswers::all();

        return view('configuration.defaultAnswer.list', ['allAnswer' => $defaultAnswer]);
    }

    public function create()
    {
        return view('configuration.defaultAnswer.create');
    }

    public function store(Request $request)
    {
        DefaultAnswers::create([
            'name' => $request->get('name'),
            'content' => $request->get('content'),
        ]);
        return redirect('/configuration/default_response');
    }

    public function edit(Request $request, ?DefaultAnswers $defaultAnswer)
    {
        if (!$defaultAnswer)
            $defaultAnswer = new DefaultAnswers();

        if ($request->exists('save_default_answer'))
        {
            $this->save_default_answer($request, $defaultAnswer);
            alert::toastSuccess(__('app.defaultAnswer.save'));
            return redirect()->route('defaultAnswers');
        }

        return view('configuration.defaultAnswer.edit')
            ->with('defaultAnswer', $defaultAnswer);
    }

    public function save_default_answer(Request $request, DefaultAnswers $defaultAnswer)
    {
        // Validate request
        $request->validate([
            'name' => 'required',
            'content' => 'nullable',
        ]);

        // Set name, content
        $defaultAnswer->name = $request->input('name');
        $defaultAnswer->content = $request->input('content');

        // Save
        $defaultAnswer->save();

        $channelSelected = $request->toArray()['channel'];
        $defaultAnswer->channels()->sync($channelSelected);
    }

    public function delete(Request $request, ?DefaultAnswers $defaultAnswer)
    {
        if($defaultAnswer->softDeleted()){
            alert::toastSuccess(__('app.defaultAnswer.delete'));
        }
        return redirect()->route('defaultAnswers');
    }

}
