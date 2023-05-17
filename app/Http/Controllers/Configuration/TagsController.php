<?php

namespace App\Http\Controllers\Configuration;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\AbstractController;
use App\Models\Channel\Channel;
use App\Models\Tags\TagList;
use App\Models\Tags\Tag;
use App\Models\Ticket\Revival\Revival;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagsController extends AbstractController
{

    public function list(Request $request): View
    {
        $query = Tag::query();
        $table = (new TableBuilder('tags', $request))
            ->setColumns(Tag::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('configuration.tags.list')
            ->with('table', $table);
    }

    public function edit(Request $request,?Tag $tags)
    {
        if (!$tags){
            $tags = new Tag();
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

    private function save_tags(Request $request, ?Tag $tags)
    {
        $request->validate([
            'name' => 'required',
            'text_color' => 'required',
            'background_color' => 'required',
        ]);

        $tags->name = $request->input('name');
        $tags->text_color = $request->input('text_color');
        $tags->background_color = $request->input('background_color');
        $tags->is_locked = $request->input('isLocked') == "on";
        $tags->save();

        $channels = $request->input('channels');
        $tags->channels()->sync($channels);
    }

    public function delete(Request $request, ?Tag $tags)
    {
        if($tags->delete()){
            alert::toastSuccess(__('app.tags.deleted'));
        }
        return redirect()->route('tags');
    }

    public function ajax_tags(Request $request)
    {
        $data = Channel::find($request->input('channel_id'))->getAuthorizedTags();
        return response()->json(['data' => $data]);
    }

    public function ajax_tags_revival(Request $request)
    {
        $data = (new Channel())->getAuthorizedTags($request->input('channel_id'));
        return response()->json(['data' => $data]);
    }

    public function newTagLine(Request $request)
    {
        $taglist = TagList::query()->max('id');
        return $taglist + 1;
    }

}
