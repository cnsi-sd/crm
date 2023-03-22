<?php

namespace App\Http\Controllers\Configuration;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableBuilder;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Http\Controllers\AbstractController;
use App\Models\Channel\SavNote;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class savNoteController extends AbstractController
{
    public function list(request $request): View|Application
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
                ->setKey('id'),
            (new TableColumnBuilder())
                ->setLabel( __('app.sav_note.manufacturer'))
                ->setType(ColumnTypeEnum::TEXT)
                ->setAlign(AlignEnum::CENTER)
                ->setKey('manufacturer'),
            (new TableColumnBuilder())
                ->setLabel( __('app.sav_note.gc_plus'))
                ->setType(ColumnTypeEnum::TEXT)
                ->setAlign(AlignEnum::CENTER)
                ->setKey('cg_plus'),
            (new TableColumnBuilder())
                ->setLabel( __('app.sav_note.hotline'))
                ->setType(ColumnTypeEnum::TEXT)
                ->setAlign(AlignEnum::CENTER)
                ->setKey('hotline'),
            TableColumnBuilder::actions()
                ->setCallback(function (SavNote $savNote) {
                    return view('configuration.sav_note.inline_table_actions')
                        ->with('savNote', $savNote);
                }),
        ];

        $table = (new TableBuilder('list_sav_notes', $request))
            ->setColumns($columns)
            ->setQuery($query);

        return view('configuration.savNote.list')
            ->with('table', $table);
    }

    public function edit(Request $request, ?savNote $savNote)
    {
        if (!$savNote)
            $savNote = new savNote();

        if ($request->exists('save_sav_note')) {
            $this->saveSavNote($request, $savNote);

            Alert::toastSuccess(__('app.sav_note.saved'));
            return redirect()->route('show_sav_note', $savNote->id);
        }

        return view('configuration.savNote.edit')
            ->with('savNote', $savNote);
    }

    public function saveSavNote(Request $request, SavNote $savNote): void
    {
        $request->validate([
            'manufacturer'          => ['required', 'string', 'max:100'],
            'pms_delay'             => ['required', 'string', 'max:100'],
            'manufacturer_warranty' => ['required', 'string', 'max:100'],
            'gc_plus'               => ['required', 'boolean'],
            'gc_plus_delay'         => ['required', 'string', 'max:255'],
            'hotline'               => ['required', 'string', 'max:255'],
            'brand_email'           => ['required', 'string', 'max:255'],
            'brand_information'     => ['nullable', 'string', 'max:255'],
            'regional_information'  => ['nullable', 'string', 'max:255'],
        ]);

        $savNote->manufacturer          = $request->input('manufacturer');
        $savNote->pms_delay             = $request->input('pms_delay');
        $savNote->manufacturer_warranty = $request->input('manufacturer_warranty');
        $savNote->gc_plus               = $request->input('gc_plus') == 'on';
        $savNote->gc_plus_delay         = $request->input('gc_plus_delay');
        $savNote->hotline               = $request->input('hotline');
        $savNote->brand_email           = $request->input('brand_email');
        $savNote->brand_information     = $request->input('brand_information');
        $savNote->regional_information  = $request->input('regional_information');

        $savNote->save();


    }

    public function show(Request $request, SavNote $savNote): View
    {
        return view('configuration.savNotes.show')
            ->with('savNote', $savNote);
    }
}
