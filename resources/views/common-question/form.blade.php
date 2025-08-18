<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('common-question.update', $id))->attribute('enctype', 'multipart/form-data')->id('common_question_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('common-question.store'))->attribute('enctype','multipart/form-data')->id('common_question_validation_form')->open() }}  
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('common-question.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>', 'goal_type')->class('form-control-label') }}
                                    {{ html()->select('goal_type', ['' => ''] + getGoalType(), $data->goal_type ?? old('goal_type'))
                                        ->class('select2js form-group type')
                                        ->id('goal_type_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))
                                        ->attribute('data-allow-clear', 'true')
                                        ->required() 
                                    }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.category') . ' <span class="text-danger">*</span>', 'category_id')->class('form-control-label') }}
                                    {{ html()->select('category_id', isset($id) ? [optional($data->category)->id => optional($data->category)->name] : [])
                                        ->class('select2js form-group')
                                        ->id('category_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.category')]))
                                        ->attribute('data-allow-clear', 'true')
                                        ->required() 
                                    }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.article'), 'article_id')->class('form-control-label') }}
                                    {{ html()->select('article_id', isset($id) ? [optional($data->article)->id => optional($data->article)->name] : [], old('article_id'))
                                        ->class('select2js form-group')
                                        ->id('article_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article']))
                                        ->attribute('data-allow-clear', 'true') 
                                    }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js')->required() }}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.question') . ' <span class="text-danger">*</span>', 'question')->class('form-control-label') }}
                                    {{ html()->textarea('question')->class('form-control')->placeholder(__('message.description'))->rows(3)->cols(40) }}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.answer') . ' <span class="text-danger">*</span>', 'answer')->class('form-control-label') }}
                                    {{ html()->textarea('answer')->class('form-control')->placeholder(__('message.description'))->rows(3)->cols(40) }}
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
                formValidation("#common_question_validation_form", {
                    title: { required: true },
                    goal_type: { required: true },
                    category_id: { required: true },
                    question: { required: true },
                    answer: { required: true },
                }, {
                    title: { required: "Please enter a Title." },
                    goal_type: { required: "Please select a goal type."},
                    category_id: { required: "Please select a Category."},
                    question: { required: "Please enter a Question."},
                    answer: { required: "Please enter a Answer."}
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
