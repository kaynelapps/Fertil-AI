<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('sub-symptoms.update', $id))->attribute('enctype', 'multipart/form-data')->id('sub_symptoms_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('sub-symptoms.store'))->attribute('enctype','multipart/form-data')->id('sub_symptoms_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('sub-symptoms.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('title') }}
                                    {{ html()->text('title')->class('form-control')->placeholder(__('message.title')) }}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.symptoms') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('symptoms_id') }}
                                    {{ html()->select('symptoms_id', isset($id) ? [optional($data->symptom)->id => optional($data->symptom)->title] : [])
                                        ->class('select2js form-group')
                                        ->id('symptoms_cat')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.symptoms')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'symptoms_category'])) 
                                    }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.article'))->class('form-control-label')->for('article_id') }}
                                    {{ html()->select('article_id', isset($id) ? [optional($data->article)->id => optional($data->article)->name] : [])
                                        ->class('select2js form-group')
                                        ->id('article_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article']))
                                        ->attribute('data-allow-clear', 'true') 
                                    }}
                                </div>

                                <div class="form-group col-md-{{ isset($id) ? '4' : '6' }}">
                                     {{ html()->label(__('message.icon'))->class('form-control-label')->for('image') }}
                                    <div class="custom-file">
                                         {{ html()->file('sub_symptom_icon')->class('custom-file-input')->accept('image/*') }}
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' => __('message.image') ]) }}</label>
                                    </div>
                                    <span class="selected_file"></span>
                                </div>

                                @if( isset($id) && getMediaFileExit($data, 'sub_symptom_icon'))
                                    <div class="col-md-2 mb-2">
                                        <img id="sub_symptom_icon_preview" src="{{ getSingleMedia($data,'sub_symptom_icon') }}" alt="sub_symptom_icon" class="attachment-image mt-1">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'sub_symptom_icon']) }}"
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
                                
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.description'))->class('form-control-label')->for('description') }}
                                    {{ html()->textarea('description')->class('form-control tinymce-description')->placeholder(__('message.description'))->rows(3)->cols(40) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('status') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')])->class('form-control select2js')->required() }}
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
            $(document).ready(function(){
                formValidation("#sub_symptoms_validation_form", {
                    title: { required: true },
                    symptoms_id: { required: true },
                }, {
                    title: { required: "Please enter a Title." },
                    symptoms_id: { required: "Please select a Symptoms."},
                });
            });
        </script>
    @endsection
</x-master-layout>
