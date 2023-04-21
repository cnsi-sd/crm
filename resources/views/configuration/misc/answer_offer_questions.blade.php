@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.default_answer_offer_questions.default_answer_offer_questions') ])

@section('misc_content')
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                {{ __('app.config.config') }}
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf
                    @include('form_components.switch', [
                        'translation' => __('app.config.misc.default_answer_offer_questions.active'),
                        'name' => 'active',
                        'value' => old('active', setting('daoq.active')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => trans_choice('app.default_answer.default_answer', 1),
                        'name' => 'default_answer_offer_questions',
                        'value' => old('default_answer_offer_questions', setting('default_answer_offer_questions'))
                    ])

                    @include('form_components.submit')
                </form>
            </div>
        </div>
    </div>
@endsection
