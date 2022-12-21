{{-- !-- Delete Warning Modal -->  --}}
<form action="{{ route('delete_revival', ['revival' => $revival]) }}" method="get"
      enctype="multipart/form-data">
    @csrf
    @method('DELETE')
    <div class="modal fade text-left" id="ModalDelete{{ $revival->id }}" tabindex="-1" role="dialog"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-center">Are you sure you want to delete : {{ $revival->name }} ?</h4>
                    <button
                        type="button"
                        class="close"
                        onclick="$('#ModalDelete{{ $revival->id }}').modal('hide');"
                        aria-label="Close"
                    >
                        x
                    </button>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        onclick="$('#ModalDelete{{ $revival->id }}').modal('hide');"
                    >
                        {{__('app.no')}}
                    </button>
                    <button type="submit" class="btn btn-danger">{{__('app.yes')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>
