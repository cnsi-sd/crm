<?php

namespace App\Http\Controllers\Configuration;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\Controller;
use App\Models\Channel\Channel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use function view;

class ChannelController extends Controller
{
    public function list(Request $request): View
    {
        $query = Channel::query();
        $table = (new TableBuilder('channels', $request))
            ->setColumns(Channel::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('configuration.channel.list')
            ->with('table', $table);
    }

    /**
     * @param Request $request
     * @param User|null $user
     * @return View|RedirectResponse
     */
    public function edit(Request $request, ?Channel $channel)
    {
        if (!$channel)
            $channel = new Channel();

        if ($request->exists('save_channel')) {
            $this->save_channel($request, $channel);
            Alert::toastSuccess(__('app.channel.saved'));
            return redirect()->route('channels');
        }

        return view('configuration.channel.edit')
            ->with('channel', $channel);
    }

    public function save_channel(Request $request, Channel $channel)
    {
        // Validate request
        $validation_rules = [
            'ext_name'          => 'required',
        ];
        $request->validate($validation_rules);

        // Set ext_name
        $channel->ext_name = $request->input('ext_name');

        // Enregistrement
        $channel->save();

    }
}
