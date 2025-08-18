<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
       @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('default-log-category.update', $id))->attribute('enctype', 'multipart/form-data')->open() }}
        @else
            {{ html()->form('POST', route('default-log-category.store'))->attribute('enctype','multipart/form-data')->open() }}  
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('default-log-category.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.name') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                                    {{ html()->text('name')->class('form-control')->placeholder(__('message.name'))->required() }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.blog') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('blog_link') }}
                                    {{ html()->select('blog_link', isset($id) ? [optional($data->article)->id => optional($data->article)->name] : [])
                                        ->class('select2js form-group article')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.blog')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article']))
                                        ->required() 
                                    }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')])->class('form-control select2js') }}
                                </div>
                                <div class="form-group col-md-6">
                                        {{ html()->label(__('message.image'))->class('form-control-label')->for('image') }}
                                    <div class="custom-file">
                                        {{ html()->file('log_category_image')->class('custom-file-input')->accept('image/*') }}
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                    </div>
                                    <span class="selected_file"></span>
                                </div>

                                @if( isset($id) && getMediaFileExit($data, 'log_category_image'))
                                    <div class="col-md-2 mb-2">
                                        <img id="log_category_image_preview" src="{{ getSingleMedia($data,'log_category_image') }}" alt="category-image" class="attachment-image mt-1">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'log_category_image']) }}"
                                            data--submit='confirm_form'
                                            data--confirmation='true'
                                            data--ajax='true'
                                            data-toggle='tooltip'
                                            title='{{ __("message.remove_file_title" , ["name" =>  __("message.image") ]) }}'
                                            data-title='{{ __("message.remove_file_title" , ["name" =>  __("message.image") ]) }}'
                                            data-message='{{ __("message.remove_file_msg") }}'>
                                            <i class="ri-close-circle-line"></i>
                                        </a>
                                    </div>
                                @endif

                                <div class="form-group col-md-12">
                                    {{ html()->label(__('message.description'))->class('form-control-label')->for('description') }}
                                    {{ html()->textarea('description')->class('form-control tinymce-description')->placeholder(__('message.description'))->rows(3)->cols(40) }}
                                </div>
                            </div>
                            <hr>
                            {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
    @section('bottom_script')
        <script>
            (function ($) {
                $(document).ready(function () {
                    tinymceEditor('.tinymce-description',' ',function (ed) { }, 450)
                });
            })(jQuery);
        </script>
    @endsection
</x-master-layout>
