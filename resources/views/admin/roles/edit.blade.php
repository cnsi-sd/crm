@extends('layouts.horizontal', ["page_title"=> trans_choice('app.role.role', 1)])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{ trans_choice('app.role.role', 1) }} #{{ $role->id }}
            </div>
            <div class="card-body">
                <form action="{{ isset($role->id) ? route('edit_role', [$role->id]) : route('create_role') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name">{{__('app.role.name')}}
                            <span class="required_field">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            class="form-control form-control-sm"
                            value="{{ old('name', $role->name ?? '') }}"
                            required
                        />
                    </div>

                    <div class="form-group mb-3">
                    </div>

                    <div class="accordion" id="permission_accordion">
                        @foreach(\App\Helpers\Permission::main() as $section_id => $section)
                            <div class="card mb-0">
                                <div class="card-header" id="{{ $section_id }}_header">
                                    <h5 class="m-0">
                                        <a class="custom-accordion-title d-block pt-2 pb-2"
                                           data-bs-toggle="collapse" href="#collapse_{{ $section_id }}"
                                           aria-expanded="true" aria-controls="collapse_{{ $section_id }}"
                                        >
                                            {{ $section['title'] }}
                                        </a>
                                    </h5>
                                </div>

                                <div id="collapse_{{ $section_id }}" class="collapse"
                                     aria-labelledby="{{ $section_id }}_header" data-bs-parent="#permission_accordion">
                                    <div class="card-body">
                                        @foreach($section['sub_sections'] as $sub_section)
                                            <h5>{{ $sub_section['title'] }}</h5>
                                            <div class="row mb-2">
                                                @foreach($sub_section['items'] as $item)
                                                    <div class="form-group col-md-3">
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" class="form-check-input" id="{{$item}}_switch" name="{{$item}}" @if(in_array($item, $permissions))checked @endif>
                                                            <label class="form-check-label" for="{{ $item }}_switch">{{\App\Enums\PermissionEnum::getMessage(strtoupper($item))}}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="border-bottom"></div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" name="save_role" class="btn btn-primary btn-block">
                            {{ __('app.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
