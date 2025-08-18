<!-- Modal -->

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{ html()->modelForm($data, 'PATCH', route('cycle-dates.update', $id))->attribute('enctype', 'multipart/form-data') }}
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="form-control-label" for="image">{{ __('message.image') }} </label>
                    <div class="custom-file">
                        <input type="file" name="thumbnail_image" class="custom-file-input" id="thumbnail_image_input" accept="image/*">
                        <label class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.image')]) }}</label>
                    </div>

                    <span class="selected_file"></span>
                </div>
                <div class="col-md-6 mb-2">
                    <img id="thumbnail_image_preview" src="" class="attachment-image mt-1">
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-md btn-secondary" data-dismiss="modal">{{ __('message.close') }}</button>
            <button type="submit" class="btn btn-md btn-primary" id="btn_submit" data-form="ajax" >{{ __('message.save') }}</button>
        </div>
        {{ html()->form()->close() }}
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#thumbnail_image_input').on('change', function(e) {
            var input = this;
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#thumbnail_image_preview').attr('src', e.target.result);
            }

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
            $(this).next('.custom-file-label').html(input.files[0].name);
        });
    });
</script>

