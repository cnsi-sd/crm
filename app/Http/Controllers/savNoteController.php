<?php

namespace App\Http\Controllers;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableBuilder;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\Channel\SavNote;
use Illuminate\Http\Request;
use Illuminate\View\View;

class savNoteController extends AbstractController
{
    public function list(request $request): View
    {
        $query = SavNote::query()
//        ->select('sav_notes.manufacturer', 'sav_notes.pms_delay', 'sav_notes.cg_plus', 'sav_notes.hotline')
        ->select('sav_notes.*')
        ->orderBy('id','DESC');

        $columns = [
            (new TableColumnBuilder())
            ->setLabel('#')
            ->setType(ColumnTypeEnum::TEXT)
            ->setAlign(AlignEnum::CENTER)
            ->setKey('id')
            ->setWhereKey('sav_notes.id'),

        ];
    }
}
