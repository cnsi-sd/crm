@extends('layouts.horizontal', ["page_title"=> isset($savNote->id) ? __('app.save_note.new') : __('app.sav_note.edit')])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    {{ trans_choice('app.sav_note.sav_note', 1) }}
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
                                        value="{{ old('pms_delay', $savNote->pmsDelay ?? '') }}"
                                        required
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="manufacturer_delay">
                                        {{__('app.sav_note.manufacturer_delay')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="manufacturer_delay"
                                        class="form-control form-control-sm"
                                        value="{{ old('manufacturer_delay', $savNote->manufacturerDelay ?? '') }}"
                                        required
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="brand_information">
                                        {{__('app.sav_note.brand_information')}}
                                    <textarea
                                        name="brand_information"
                                        class="form-control form-control-sm"
                                    >
                                        {{ old('brand_information', $savNote->brandInformation ?? '') }}
                                    </textarea>
                                    </label>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gc_plus">
                                        {{__('app.sav_note.gc_plus')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="gc_plus"
                                        class="form-control form-control-sm"
                                        value="{{ old('gc_plus', $savNote->gcPlus ?? '') }}"
                                        required
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="gc_plus_delay">
                                        {{__('app.sav_note.gc_plus_delay')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="gc_plus_delay"
                                        class="form-control form-control-sm"
                                        value="{{ old('gc_plus_delay', $savNote->gcPlusDelay ?? '') }}"
                                        required
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
                                        value="{{ old('brand_email', $savNote->brandEmail ?? '') }}"
                                        required
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="regional_information">
                                        {{__('app.sav_note.regional_information')}}
                                        <textarea
                                            name="regional_information"
                                            class="form-control form-control-sm"
                                        >
                                        {{ old('regional_information', $savNote->regionalInformation ?? '') }}
                                    </textarea>
                                    </label>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" name="save" class="btn btn-primary btn-block">
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
