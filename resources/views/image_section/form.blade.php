<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('image-section.update', $id))->attribute('enctype', 'multipart/form-data')->id('image_section_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('image-section.store'))->attribute('enctype','multipart/form-data')->id('image_section_validation_form')->open() }}  
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('image-section.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                     {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('title') }}
                                     {{ html()->text('title')->class('form-control')->placeholder(__('message.title'))->required() }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.article') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('article_id') }}
                                    {{ html()->select('article_id', isset($id) ? [optional($data->article)->id => optional($data->article)->name] : [])
                                        ->class('select2js form-group article')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article']))
                                        ->required() 
                                    }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('goal_type') }}
                                    {{ html()->select('goal_type', ['' => ''] + getGoalType(), $data->type ?? old('goal_type'))
                                        ->class('select2js form-group type')
                                        ->id('goal_type_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))
                                        ->attribute('data-allow-clear', 'true')
                                        ->required()
                                    }}
                                </div>
                                <div class="form-group col-md-6" id="for_cateogry">
                                     {{ html()->label(__('message.category') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('category_id') }}
                                     {{ html()->select('category_id', isset($id) ? [optional($data->category)->id => optional($data->category)->name] : ['' => ''])
                                        ->class('select2js form-group')
                                        ->id('category_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.category')]))
                                        ->attribute('data-allow-clear', 'true') 
                                    }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js')->required() }}
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-control-label" for="image">{{ __('message.image') }} </label>
                                    <div class="custom-file">
                                        <input type="file" name="image_section_thumbnail_image" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                    </div>
                                    <span class="selected_file"></span>
                                </div>

                                @if( isset($id) && getMediaFileExit($data, 'image_section_thumbnail_image'))
                                    <div class="col-md-2 mb-2">
                                        <img id="image_section_thumbnail_image_preview" src="{{ getSingleMedia($data,'image_section_thumbnail_image') }}" alt="category-image" class="attachment-image mt-1">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'image_section_thumbnail_image']) }}"
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
                formValidation("#image_section_validation_form", {
                    title: { required: true },
                    goal_type: { required: true },
                    article_id: { required: true },
                    category_id: { required: true },
                }, {
                    title: { required: "Please enter a Title." },
                    goal_type: { required: "Please select a goal type."},
                    article_id: { required: "Please select a Article."},
                    category_id: { required: "Please select a Category."}
                });
            });
        </script>
        <script>
            (function ($) {
                var state = "{{ old('category_id') ?? (isset($id) ? optional($data->category)->id : '') }}";

                var goal_type_id = $('#goal_type_id').val();
                $(document).on('change', '#goal_type_id', function () {
                    goal_type_id = $(this).val();
                    runFunctionAfterChange();
                });
                runFunctionAfterChange();

                function runFunctionAfterChange() {
                    var goal_type = goal_type_id;
                    $('#category_id').empty();
                    goalTypetList(goal_type);
                }
                function goalTypetList(goal_type) {
                    var goal_type_route = "{{ route('ajax-list', ['type' => 'get_category_by_goal_type', 'goal_type' => '']) }}" + goal_type;
                    goal_type_route = goal_type_route.replace('amp;', '');

                    $.ajax({
                        url: goal_type_route,
                        success: function (result) {
                            $('#category_id').select2({
                                width: '100%',
                                placeholder: "{{ __('message.select_name', ['select' => __('message.category')]) }}",
                                data: result.results
                            });

                            if (state !== null) {
                                $('#category_id').val(state).trigger('change');
                            }
                        }
                    });
                }
            })(jQuery);
        </script>
    @endsection
</x-master-layout>
