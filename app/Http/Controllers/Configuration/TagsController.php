<?php

namespace App\Http\Controllers\Configuration;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\Controller;
use App\Models\Tags\TagList;
use App\Models\Tags\Tags;
use App\Models\Ticket\Revival\Revival;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagsController extends Controller
{

    public function list(Request $request): View
    {
        $query = Tags::query();
        $table = (new TableBuilder('tags', $request))
            ->setColumns(Tags::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('configuration.tags.list')
            ->with('table', $table);
    }

    public function edit(Request $request,?Tags $tags)
    {
        if (!$tags){
            $tags = new Tags();
        }

        if ($request->exists('save_tags'))
        {
            $this->save_tags($request, $tags);
            alert::toastSuccess(__('app.save'));
            return redirect()->route('tags');
        }

        return view('configuration.tags.edit')
            ->with('tags', $tags);
    }

    private function save_tags(Request $request, ?Tags $tags)
    {
        $request->validate([
            'name' => 'required',
            'text_color' => 'required',
            'background_color' => 'required',
        ]);

        $tags->name = $request->input('name');
        $tags->text_color = $request->input('text_color');
        $tags->background_color = $request->input('background_color');
        $tags->save();

        $tags->channels()->sync($request->input('channel'));
    }

    public function delete(Request $request, ?Tags $tags)
    {
        if($tags->delete()){
            alert::toastSuccess(__('app.delete'));
        }
        return redirect()->route('tags');
    }

    public function ajax_tags(){
        $data = Tags::all();

        return response()->json(['data' => $data]);
    }

    public function newTagLine(Request $request) {
        $taglist = new TagList();
        $taglist->thread_id =$request->input('thread_id');
        $taglist->save();
        return response()->json($taglist->id);
    }

}
