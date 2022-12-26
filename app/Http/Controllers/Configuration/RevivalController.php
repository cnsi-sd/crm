<?php

namespace App\Http\Controllers\Configuration;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\Controller;
use App\Models\Ticket\Revival\Revival;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RevivalController extends Controller
{

    public function list(Request $request): View
    {
        $query = Revival::query();
        $table = (new TableBuilder('defaultAnswers', $request))
            ->setColumns(Revival::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('configuration.revival.list')
            ->with('table', $table);
    }

    public function index(){
        $revival = Revival::all();

        return view('configuration.defaultAnswer.list', ['allRevival' => $revival]);
    }

    public function edit(Request $request, ?Revival $revival)
    {
        if (!$revival){
            $revival = new Revival();
        }

        if ($request->exists('save_default_answer'))
        {
            $this->save_revival($request, $revival);
            alert::toastSuccess(__('app.save'));
            return redirect()->route('revival');
        }

        return view('configuration.revival.edit')
            ->with('revival', $revival);
    }
    public function save_revival(Request $request, Revival $revival)
    {
        // Validate request
        $request->validate([
            'name' => 'required',
            'frequency' => ['required', 'min:1'],
            'max_revival' => ['required', 'min:1'],
            'end_state' => 'required'
        ]);

        // Set name, content
        $revival->name = $request->input('name');
        $revival->frequency = $request->input('frequency');
        $revival->default_answer_id = $request->input('default_answer_id');
        $revival->max_revival = $request->input('max_revival');
        $revival->end_default_answer_id = $request->input('end_default_answer_id');
        $revival->end_state = $request->input('end_state');

        // Save
        $revival->save();

        $channelSelected = $request->toArray()['channel'];
        $revival->channels()->sync($channelSelected);
    }

    public function delete(Request $request, ?Revival $revival)
    {
        if($revival->softDeleted()){
            alert::toastSuccess(__('app.delete'));
        }
        return redirect()->route('revival');
    }

}
