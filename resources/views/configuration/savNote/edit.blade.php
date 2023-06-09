@extends('layouts.horizontal', ["page_title"=> isset($savNote->id) ? __('app.sav_note.edit') : __('app.sav_note.new')])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    {{ trans_choice('app.sav_note.sav_note', 1) }}
                    @if(isset($savNote->id))
                        @can('delete', $savNote)
                            <a
                                class="btn btn-outline-danger btn-sm float-end"
                                data-confirm="{{ __('app.sav_note.delete_confirm') }}"
                                target="_blank"
                                href="{{ route('delete_sav_note', ['savNote' => $savNote]) }}"
                            >
                                <i class="uil-trash"></i>
                                {{ __('app.delete') }}
                            </a>
                        @endcan
                    @endif
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($savNote->id) ? route('edit_sav_note', [$savNote->id]) : route('create_sav_note') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="manufacturer">
                                        {{__('app.sav_note.manufacturer')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="manufacturer"
                                        class="form-control form-control-sm"
                                        value="{{ old('manufacturer', $savNote->manufacturer ?? '') }}"
                                        required
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="pms_delay">
                                        {{__('app.sav_note.pms_delay')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="pms_delay"
                                        class="form-control form-control-sm"
                                        value="{{ old('pms_delay', $savNote->pms_delay ?? '') }}"
                                        required
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="manufacturer_delay">
                                        {{__('app.sav_note.manufacturer_warranty')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="manufacturer_warranty"
                                        class="form-control form-control-sm"
                                        value="{{ old('manufacturer_warranty', $savNote->manufacturer_warranty ?? '') }}"
                                        required
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="brand_information">
                                        {{__('app.sav_note.brand_information')}}
                                    <textarea
                                        name="brand_information"
                                        class="form-control form-control-sm"
                                        cols="125"
                                        rows="10"
                                    >{{ old('brand_information', $savNote->brand_information ?? '') }}</textarea>
                                    </label>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="gc_plus_checkbox" name="gc_plus" @if($savNote->gc_plus) checked @endif>
                                        <label class="form-check-label" for="gc_plus">
                                            {{ __('app.sav_note.gc_plus') }}
                                            <span class="required_field">*</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group mb-3" id="gc_plus_delay">
                                    <label for="gc_plus_delay">
                                        {{__('app.sav_note.gc_plus_delay')}}
                                        <span class="required_field" hidden="true">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="gc_plus_delay"
                                        class="form-control form-control-sm"
                                        value="{{ old('gc_plus_delay', $savNote->gc_plus_delay ?? '') }}"
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="hotline">
                                        {{__('app.sav_note.hotline')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="hotline"
                                        class="form-control form-control-sm"
                                        value="{{ old('hotline', $savNote->hotline ?? '') }}"
                                        required
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="brand_email">
                                        {{__('app.sav_note.brand_email')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="brand_email"
                                        class="form-control form-control-sm"
                                        value="{{ old('brand_email', $savNote->brand_email ?? '') }}"
                                        required
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="supplier_information">
                                        {{__('app.sav_note.supplier_information')}}
                                        <textarea
                                            name="supplier_information"
                                            class="form-control form-control-sm"
                                            cols="125"
                                            rows="10"
                                        >{{ old('supplier_information', $savNote->supplier_information ?? '') }}</textarea>
                                    </label>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" name="save_sav_note" class="btn btn-primary btn-block">
                                    {{ __('app.save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-bottom')
    <script src="{{ Vite::asset('resources/js/savNotes.js') }}"></script>
@endsection
