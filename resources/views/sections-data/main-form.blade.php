<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('section-data-main.update', $id))->attribute('enctype', 'multipart/form-data')->id('section_data_main_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('section-data-main.store'))->attribute('enctype','multipart/form-data')->id('section_data_main_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('section-data-main.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>', 'title')->class('form-control-label') }}
                                    {{ html()->text('title')->placeholder(__('message.title'))->class('form-control')->required() }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>', 'goal_type')->class('form-control-label')  }}
                                    {{ html()->select('goal_type', ['' => ''] + getGoalType(), $data->type ?? old('goal_type'))
                                        ->class('select2js form-group type')
                                        ->id('goal_type_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))
                                        ->attribute('data-allow-clear', 'true')
                                        ->required()
                                    }}
                                </div>
                                <div class="form-group col-md-6" id="for_cateogry">
                                     {{ html()->label(__('message.category') . ' <span class="text-danger">*</span>', 'category_id')->class('form-control-label') }}
                                    {{ html()->select('category_id', isset($id) ? [optional($data->category)->id => optional($data->category)->name] : ['' => ''], old('category_id'))
                                        ->class('select2js form-group')
                                        ->id('category_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.category')]))
                                        ->attribute('data-allow-clear', 'true')
                                    }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js') ->required() }}
                                </div>

                                <div class="col-md-6 ml-3 custom-control custom-checkbox custom-inline">
                                    <input type="hidden" name="is_show_insights" value="0">
                                    <input type="checkbox" name="is_show_insights" value="1" class="custom-control-input" id="is_show_insights"
                                    {{ old('is_show_insights', isset($data) ? $data->is_show_insights : false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_show_insights">{{__('message.show_in_insights')}}<label>
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
            formValidation("#section_data_main_validation_form", {
                title: { required: true },
                goal_type: { required: true },
                category_id: { required: true },
            }, {
                title: { required: "Please enter a Title." },
                goal_type: { required: "Please select a goal type."},
                category_id: { required: "Please select a Category."}
            });
        });
    </script>
    <script>
        (function ($) {
            $(document).ready(function () {
                $(document).on('change', '#view_type_id', function () {
                    var view_id = $(this).val();
                    if (view_id == 0){
                        $("#for_story_image").removeClass('d-none');
                    } else {
                        $("#for_story_image").addClass('d-none');
                    }

                    if (view_id == 1){
                        $("#video_upload").removeClass('d-none');
                    } else {
                        $("#video_upload").addClass('d-none');
                    }

                    if (view_id == 2){
                        $("#for_cateogry").removeClass('d-none');
                    } else {
                        $("#for_cateogry").addClass('d-none');

                    }
                });

                var view_type_data = "{{ old('view_type_id') ?? (isset($data) ? $data->view_type : '') }}";
                var category_id = "{{ old('category_id') ?? (isset($data) ? $data->category_id : '') }}";

                if (view_type_data == 0 && view_type_data != '') {
                    $("#for_story_image").removeClass('d-none');
                }

                if (view_type_data == 2 && view_type_data != '') {
                    $("#goal_type_id").trigger('change')
                    $("#for_cateogry").removeClass('d-none');
                }

                if (view_type_data == 1 && view_type_data != '') {
                    $("#video_upload").removeClass('d-none');
                }
            });
        })(jQuery);
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
