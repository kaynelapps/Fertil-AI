<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        {{-- Modal Header --}}
        <div class="modal-header">
            <h5 class="modal-title"><b>{{ $data->title ?? '' }}</b></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="modal-body">
            <div class="form-group">
                <div class="mt-2">
                    {!! htmlspecialchars_decode($data->description) !!}
                </div>
            </div>
        </div>
    </div>
</div>
