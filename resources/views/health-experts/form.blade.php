<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('health-experts.update', $id))->attribute('enctype', 'multipart/form-data')->id('health_expert_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('health-experts.store'))->attribute('enctype','multipart/form-data')->id('health_expert_validation_form')->open() }}  
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('health-experts.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.name') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                                    {{ html()->text('name')->value(isset($data) ? optional($data->users)->first_name : null)->placeholder(__('message.name'))->class('form-control')->attribute('autofocus', true) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.email') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                                    {{ html()->text('email')->value(isset($data) ? optional($data->users)->email : null)->placeholder(__('message.email'))->class('form-control')->attributeIf(isset($id), 'disabled', true) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.tag_line') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                                    {{ html()->text('tag_line')->placeholder(__('message.tag_line'))->class('form-control') }}
                                </div>
                                <div class="form-group col-md-{{ isset($id) && getMediaFileExit($data, 'health_experts_image') ? '5' : '6' }}">
                                    {{ html()->label(__('message.image'))->class('form-control-label') }}
                                    <div class="custom-file">
                                        {{ html()->file('health_experts_image')->class('custom-file-input')->attribute('accept', 'image/*') }}
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                    </div>
                                    <span class="selected_file"></span>

                                </div>

                                @if( isset($id) && getMediaFileExit($data, 'health_experts_image'))
                                    <div class="col-md-1 mb-2">
                                        <img id="health_experts_image_preview" src="{{ getSingleMedia($data,'health_experts_image') }}" alt="health-experts-image" class="attachment-image mt-2">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'health_experts_image']) }}"
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
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                                    {{ html()->select('status', ['active' => __('message.active'), 'inactive' => __('message.inactive')],  isset($id) ? optional($data->users)->status : old('status'))->class('form-control select2js')->required() }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.year_of_experience') . ' <span class="text-danger"></span>')->class('form-control-label') }}
                                    {{ html()->select('exprince', 
                                        isset($id) ? [$data->exprince => $data->exprince] : [], 
                                        old('exprince', isset($data) ? $data->exprince : null))
                                        ->class('select2js form-group')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.year_of_experience')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'experince_health_expert']))
                                        ->attribute('data-allow-clear', 'true') 
                                    }}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.gender').' <span class="text-danger">*</span>')->for('gender')->class('form-control-label') }}
                                    {{ html()->select('gender', ['male'   => __('message.male'),'female' => __('message.female'),'other'  => __('message.other') ], old('gender', isset($id) ? $data->gender : null))->class('form-control select2js')->required()->id('gender') }}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.short_description') . ' <span class="text-danger">*</span>')->class('form-control-label font-weight-bold') }}
                                    {{ html()->textarea('short_description')->class('form-control tinymce-description')->placeholder(__('message.short_description'))->attribute('rows', 3) ->attribute('cols', 40) }}
                                </div>


                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.career'))->class('form-control-label font-weight-bold') }}
                                    {{ html()->textarea('career')->class('form-control tinymce-description')->placeholder(__('message.career'))->attribute('rows', 3)->attribute('cols', 40) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.education'))->class('form-control-label font-weight-bold') }}
                                    {{ html()->textarea('education')->class('form-control tinymce-description')->placeholder(__('message.education'))->attribute('rows', 3)->attribute('cols', 40) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.awards_achievements'))->class('form-control-label font-weight-bold') }}
                                    {{ html()->textarea('awards_achievements')->class('form-control tinymce-description')->placeholder(__('message.awards_achievements'))->attribute('rows', 3)->attribute('cols', 40) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.area_expertise'))->class('form-control-label font-weight-bold') }}
                                    {{ html()->textarea('area_expertise')->class('form-control tinymce-description')->placeholder(__('message.area_expertise'))->attribute('rows', 3)->attribute('cols', 40) }}
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
                formValidation("#health_expert_validation_form", {
                    name: { required: true },
                    email: { required: true },
                    tag_line: { required: true },
                    short_description: { required: true },
                }, {
                    name: { required: "Please enter a Name." },
                    email: { required: "Please select a Email."},
                    tag_line: { required: "Please select a Tag Line."},
                    short_description: { required: "Please select a Short Description."},

                });
            });
        </script>
        <script>
            (function($) {
                $(document).ready(function() {
        
                    tinymce.init({
                        selector: '.tinymce-description',
                        menubar: false,
                        plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                            'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                            'insertdatetime', 'media', 'table', 'help', 'wordcount'
                        ],
                        toolbar: 'undo redo | blocks | bold italic | bullist numlist outdent indent | removeformat | help',
                        height: 300,
                        branding: false
                    });
                });
            })(jQuery);
        </script>
    @endsection
</x-master-layout>
