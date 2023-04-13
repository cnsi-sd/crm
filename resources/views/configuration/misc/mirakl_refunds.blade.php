@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.mirakl_refunds') ])

@section('misc_content')
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                {{ __('app.config.config') }}
            </div>

            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf

                    @include('form_components.tag_select', [
                        'translation' => __('app.config.misc.mirakl_refunds_tag'),
                        'name' => 'mirakl_refunds_tag_id',
                        'value' => old('mirakl_refunds_tag_id', setting('mirakl_refunds_tag_id')),
                    ])

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="save">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
@endsection
