<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('pregnancy-date.update', $id))->attribute('enctype', 'multipart/form-data')->id('pregnancy_date_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('pregnancy-date.store'))->attribute('enctype','multipart/form-data')->id('pregnancy_date_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('pregnancy-date.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{html()->label(__('message.pregnancy_date') . ' <span class="text-danger">*</span>', 'pregnancy_date')->class('form-control-label')}}
                                    {{html()->select('pregnancy_date')->options(isset($id) ? [ $data->pregnancy_date => $data->pregnancy_date . ' ' . __('message.week') ] : [])->value(old('pregnancy_date'))->class('select2js form-group')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.pregnancy_date')]))->attribute('data-ajax--url', route('ajax-list', ['type' => 'pregnancy_date']))->attribute('data-allow-clear', 'true')}}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>', 'title')->class('form-control-label') }}
                                    {{ html()->text('title', old('title'))->placeholder(__('message.title'))->class('form-control') }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.article') . ' <span class="text-danger">*</span>', 'article_id')->class('form-control-label') }}
                                    {{ html()->select('article_id', isset($id) ? [optional($data->article)->id => optional($data->article)->name] : [], old('article_id'))->class('select2js form-group article')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article'])) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js') }}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label class="form-control-label" for="image">{{ __('message.image') }} </label>
                                    <div class="custom-file">
                                        <input type="file" name="pregnancy_date_image" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                    </div>
                                    <span class="selected_file"></span>
                                </div>

                                @if( isset($id) && getMediaFileExit($data, 'pregnancy_date_image'))
                                    <div class="col-md-2 mb-2">
                                        <img id="pregnancy_date_image_preview" src="{{ getSingleMedia($data,'pregnancy_date_image') }}" alt="category-image" class="attachment-image mt-1">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'pregnancy_date_image']) }}"
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
            $(document).ready(function(){
                formValidation("#pregnancy_date_validation_form", {
                    title: { required: true },
                    article_id: { required: true },
                    pregnancy_date: { required: true },
                }, {
                    title: { required: "Please enter a Title." },
                    article_id: { required: "Please select a Article."},
                    pregnancy_date: { required: "Please select a Pregnancy Week."},
                });
            });
        </script>
    @endsection
</x-master-layout>
