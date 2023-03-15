<div class="card">
    <div class="card-header">
        <i class="uil-books"></i> {{ trans_choice('attachments::attachments.document.document', 2) }}
        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
            {{ __('attachments::attachments.document.new') }}
        </button>
    </div>
    <div class="card-body">
        {{ $table->render() }}
    </div>
</div>

<div class="modal fade" id="addDocumentModal" tabindex="-2" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('attachments::attachments.document.new') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ $upload_document_route }}"
                      method="post"
                      class="dropzone"
                      data-plugin="dropzone"
                >
                    @csrf

                    <select class="no-select2 form-control form-control-sm form-select" name="type">
                        @foreach($allowed_types as $allowed_type)
                            <option value="{{ $allowed_type }}">{{ \App\Enums\CrmDocumentTypeEnum::getMessage($allowed_type) }}</option>
                        @endforeach
                    </select>

                    <input type="hidden" name="documentable_type" value="{{ $documentable_type }}">
                    <input type="hidden" name="documentable_id" value="{{ $documentable_id }}">

                    <div class="fallback">
                        <input name="file" type="file"/>
                    </div>

                    <div class="dz-message d-flex flex-column text-center">
                        <i class="h1 text-muted uil-cloud-upload" style="font-size: 2.5rem;"></i>
                        <h5>{{ trans_choice('attachments::attachments.drop_file', 1) }}</h5>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('attachments::attachments.close') }}</button>
            </div>
        </div>
    </div>
</div>



<div id="dropzone-upload-modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                {{ __('attachments::attachments.uploading') }}
            </div>
            <div class="modal-body">
                <div class="progress progress-xl progress-bar-striped">
                    <div id="dropzone-upload-progress"
                         class="progress-bci !ar"
                         role="progressbar"
                         aria-valuenow="25"
                         aria-valuemin="0"
                         aria-valuemax="100"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script-bottom')
    <!-- plugin js -->
    <script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
    <!-- init js -->
    <script src="{{ asset('assets/js/component.fileupload.js') }}"></script>
@endsection
