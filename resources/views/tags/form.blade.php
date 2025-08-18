<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('tags.update', $id))->attribute('enctype', 'multipart/form-data')->id('tags_form_validation')->open() }}
        @else
            {{ html()->form('POST', route('tags.store'))->attribute('enctype','multipart/form-data')->id('tags_form_validation')->open() }}
        @endif
        <div class="modal-body">
            <div class="form-group">
                <div class="form-group col-md-12">
                    {{ html()->label(__('message.name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                    {{ html()->text('name', old('name'))->placeholder(__('message.name'))->class('form-control mb-1')->required() }}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right')->id('btn_submit')->attribute('data-form', 'ajax-submite-jquery-validation') }}
            <button type="button" class="btn btn-md btn-secondary float-right mr-1" data-dismiss="modal">{{ __('message.close') }}</button>
        </div>
        {{ html()->form()->close() }}
    </div>
</div>
