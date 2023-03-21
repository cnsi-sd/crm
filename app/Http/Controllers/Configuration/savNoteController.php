<?php

namespace App\Http\Controllers\Configuration;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Http\Controllers\AbstractController;
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
            (new TableColumnBuilder())
            ->setLabel( __('app.sav_note.manufacturer'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setAlign(AlignEnum::CENTER)
            ->setKey('manufacturer')
            (new TableColumnBuilder())
            ->setLabel( __('app.sav_note.cg_plus'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setAlign(AlignEnum::CENTER)
            ->setKey('cg_plus')
            (new TableColumnBuilder())
            ->setLabel( __('app.sav_note.hotline'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setAlign(AlignEnum::CENTER)
            ->setKey('hotline'),
            $columns[] = TableColumnBuilder::actions()
                ->setCallback(function (SavNote $savNote) {
                    return view('configuration.sav_note.inline_table_actions')
                        ->with('savNote', $savNote);
                });

        ];
    }
}
