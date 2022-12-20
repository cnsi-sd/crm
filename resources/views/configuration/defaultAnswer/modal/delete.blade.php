{{-- !-- Delete Warning Modal -->  --}}
<form action="{{ route('delete_defaultAnswers', ['defaultAnswer' => $defaultAnswer]) }}" method="get"
      enctype="multipart/form-data">
    @csrf
    @method('DELETE')
    <div class="modal fade text-left" id="ModalDelete{{ $defaultAnswer->id }}" tabindex="-1" role="dialog"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-center">Are you sure you want to delete {{ $defaultAnswer->name }} ?</h4>
                    <button
                        type="button"
                        class="close"
                        onclick="$('#ModalDelete{{ $defaultAnswer->id }}').modal('hide');"
                        aria-label="Close"
                    >
                        x
                    </button>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        onclick="$('#ModalDelete{{ $defaultAnswer->id }}').modal('hide');"
                    >
                        {{__('app.defaultAnswer.close')}}
                    </button>
                    <button type="submit" class="btn btn-danger">{{__('app.defaultAnswer.delete')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>
