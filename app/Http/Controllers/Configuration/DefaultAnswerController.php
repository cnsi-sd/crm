<?php

namespace App\Http\Controllers\Configuration;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\Controller;
use App\Models\Channel\DefaultAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use function view;

class DefaultAnswerController extends Controller
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
            alert::toastSuccess(__('app.defaultAnswer.save'));
            return redirect()->route('defaultAnswers');
        }

        return view('configuration.defaultAnswer.edit')
            ->with('defaultAnswer', $defaultAnswer);
    }

    public function save_default_answer(Request $request, DefaultAnswer $default_Answer)
    {
        // Validate request
        $request->validate([
            'name' => 'required',
            'content' => 'nullable',
        ]);

        // Set name, content
        $default_Answer->name = $request->input('name');
        $default_Answer->content = $request->input('content');

        // Save
        $default_Answer->save();
        $defaultAnswerScript = $request->toArray()['channel'];
        $defaultAnswerDb = [];

        foreach ($default_Answer->channels as $channel)
        {
            $defaultAnswerDb[] = $channel->id;
        }

        $deleteAnswers = array_diff($defaultAnswerDb, $defaultAnswerScript);
        $insertAnswers = array_diff($defaultAnswerScript, $defaultAnswerDb);

        foreach ($deleteAnswers as $delete)
        {
            DB::table('channel_default_answer')
                ->where('channel_id', $delete)
                ->where('default_answer_id', $default_Answer->id)
                ->delete();
        }

        foreach ($insertAnswers as $insert)
        {
            DB::table('channel_default_answer')
                ->insert([
                    "channel_id" => $insert,
                    "default_answer_id" => $default_Answer->id,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
        }
    }

    public function delete(Request $request, ?DefaultAnswer $defaultAnswer)
    {
        if($defaultAnswer->softDeleted()){
            alert::toastSuccess(__('app.defaultAnswer.delete'));
        }
        return redirect()->route('defaultAnswers');
    }

}
