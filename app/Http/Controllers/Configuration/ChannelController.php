<?php

namespace App\Http\Controllers\Configuration;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Helpers\Prestashop\CrmLinkGateway;
use App\Http\Controllers\AbstractController;
use App\Models\Channel\Channel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use function view;

class ChannelController extends AbstractController
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
            ->with('ext_channels', (new CrmLinkGateway())->getChannels())
            ->with('channel', $channel);
    }

    public function save_channel(Request $request, Channel $channel)
    {
        // Validate request
        $validation_rules = [
            'ext_names' => ['required', 'array'],
            'order_url' => ['nullable', 'string'],
        ];
        $request->validate($validation_rules);

        // Set ext_names
        $channel->ext_names = $request->input('ext_names');
        $channel->order_url = $request->input('order_url');

        // Enregistrement
        $channel->save();

    }
}
