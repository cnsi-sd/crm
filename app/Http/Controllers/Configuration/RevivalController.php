<?php

namespace App\Http\Controllers\Configuration;

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

      public function create()
    {
        //
    }

       public function store(Request $request)
    {
        //
    }

    public function show(RevivalController $revival)
    {
        //
    }

    public function edit(RevivalController $revival)
    {
        //
    }

    public function update(Request $request, RevivalController $revival)
    {
        //
    }

    public function destroy(RevivalController $revival)
    {
        //
    }
}
