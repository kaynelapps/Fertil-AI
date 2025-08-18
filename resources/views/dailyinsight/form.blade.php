<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
       @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('dailyInsight.update', $id))->attribute('enctype', 'multipart/form-data')->id('dailyinsights_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('dailyInsight.store'))->attribute('enctype','multipart/form-data')->id('dailyinsights_validation_form')->open() }}  
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('dailyInsight.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.title').' <span class="text-danger">*</span>', 'title')->class('form-control-label') }}
                                    {{ html()->text('title', old('title'))->placeholder(__('message.title'))->class('form-control') }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>', 'goal_type')->class('form-control-label') }}
                                    {{ html()->select('goal_type', ['' => ''] + getGoalType(), $data->type ?? old('goal_type'))
                                        ->class('select2js form-group type')
                                        ->id('goal_type_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))
                                        ->attribute('data-allow-clear', 'true')
                                        ->required()
                                    }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.sub_symptoms') . ' <span class="text-danger">*</span>', 'sub_symptoms_id')->class('form-control-label')->attribute('for', 'sub_symptoms_id') }}    

                                    {{ html()->select('sub_symptoms_id[]', $selectedSubsymptoms ?? [], isset($id) ? $ids : [])
                                        ->class('select2js form-group')
                                        ->multiple('multiple')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.sub_symptoms')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'sub_symptoms_category']))
                                        ->attribute('required', 'required')
                                    }}
                                </div>
                                 <div class="form-group col-md-6" id="article_container">
                                    {{ html()->label(__('message.phase') . ' <span class="text-danger">*</span>', 'phase')->class('form-control-label') }}
                                    {{ html()->select('phase', ['' => ''] + getArticleType(), $data->phase ?? ('phase'))->class('select2js form-group type')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.phase')]))->attribute('data-allow-clear', 'true') }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>')->for('status')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js')->required() }}
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
            $(document).ready(function() {
                formValidation("#dailyinsights_validation_form", {
                    title: { required: true },
                    goal_type: { required: true },
                    phase: { required: true },
                    "sub_symptoms_id[]": { required: true },

                }, {
                    title: { required: "Please enter a Title." },
                    goal_type: { required: "Please select a goal type."},
                    phase: { required: "Please select a Phase."},
                    "sub_symptoms_id[]": { required: "Please select a sub symptoms."},
                });
            });
        </script>
    @endsection
</x-master-layout>
