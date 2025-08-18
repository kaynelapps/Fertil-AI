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
            {{ html()->modelForm($data, 'PATCH', route('languagewithkeyword.update', $id))->open() }}
        @else
            {{ html()->form('POST', route('languagewithkeyword.store'))->open() }}
        @endif
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group col-md-12">
                        {{ html()->label(__('message.language', ['select' => __('message.language')]))->class('form-control-label')->for('language') }}
                        {{ html()->select('language', isset($data) ? [ $data->languagelist->id => optional($data->languagelist)->language_name ] : [])->class('form-control select2 language')->attribute('disabled', true) }}
                    </div>
                    <div class="form-group col-md-12">
                        {{ html()->label(__('message.keyword_title', ['select' => __('message.keyword')]))->class('form-control-label')->for('keyword') }}
                        {{ html()->select('keyword', isset($data) ? [ $data->defaultkeyword->id => optional($data->defaultkeyword)->keyword_name ] : [])->class('form-control select2 keyword')->attribute('disabled', true) }}
                    </div>
                    <div class="form-group col-md-12">
                        {{ html()->label(__('message.keyword_value') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('keyword_value') }}
                        {{ html()->text('keyword_value', old('keyword_value'))->class('form-control')->placeholder(__('message.keyword_value'))->required() }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right')->id('btn_submit')->attribute('data-form', 'ajax')  }}
                <button type="button" class="btn btn-md btn-secondary float-right mr-1" data-dismiss="modal">{{ __('message.close') }}</button>
            </div>
        {{ html()->form()->close() }}   
    </div>
</div>
{{-- <script>
    $('#screenName').select2({
        width: '100%',
        placeholder: "{{ __('message.select_name',['select' => __('message.screen_name')]) }}",
    });
</script> --}}