@extends('layouts.horizontal', ["page_title"=> trans_choice('app.sav_note.sav_note', 1)])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card col-lg-6 m-auto">
                <div class="card-header my-2">
                    <div>
                        <i class="uil-document"></i> {{ trans_choice('app.sav_note.sav_note', 1) }}
                        - <span class="text-black"> {{ $savNote->manufacturer }}</span>
                        @can('edit', $savNote)
                            <a href="{{ route('edit_sav_note', [$savNote])}}"
                               class="btn btn-primary btn-sm float-end mt-n1"
                               title="{{ __('app.edit') }}"
                            >
                                <i class="uil-edit"></i>
                                {{ __('app.sav_note.edit') }}
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <table class="table mb-3">
                        <tbody>
                        <tr>
                            <td class="table-active text-end col-5">{{ __('app.sav_note.manufacturer') }}</td>
                            <td>{{ $savNote->manufacturer }}</td>
                        </tr>
                        <tr>
                            <td class="table-active text-end">{{ __('app.sav_note.pms_delay') }}</td>
                            <td>{{ $savNote->pms_delay }}</td>
                        </tr>
                        <tr>
                            <td class="table-active text-end">{{ __('app.sav_note.manufacturer_warranty') }}</td>
                            <td>{{ $savNote->manufacturer_warranty }}</td>
                        </tr>
                        <tr>
                            <td class="table-active text-end">{{ __('app.sav_note.gc_plus') }}</td>
                            <td> @if($savNote->gc_plus  == '1') OUI @else NON @endif </td>
                        </tr>
                        @if($savNote->gc_plus  == '1')
                            <tr>
                                <td class="table-active text-end">{{ __('app.sav_note.gc_plus_delay') }}</td>
                                <td>{{ $savNote->gc_plus_delay }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="table-active text-end">{{ __('app.sav_note.hotline') }}</td>
                            <td>{{ $savNote->hotline }}</td>
                        </tr>
                        <tr>
                            <td class="table-active text-end">{{ __('app.sav_note.brand_email') }}</td>
                            <td>{{ $savNote->brand_email }}</td>
                        </tr>
                        <tr>
                            <td class="table-active text-end">{{ __('app.sav_note.brand_information') }}</td>
                            <td style="word-break:break-word"><p>{!! nl2br($savNote->brand_information) !!}</p></td>
                        </tr>
                        <tr>
                            <td class="table-active text-end">{{ __('app.sav_note.supplier_information') }}</td>
                            <td style="word-break:break-word">{!! nl2br($savNote->regional_information) !!}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
