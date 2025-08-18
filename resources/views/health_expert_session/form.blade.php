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
                {{ html()->modelForm($data, 'PATCH', route('healthexpert-session.update', $id))->open() }}
            @else
                {{ html()->form('POST', route('healthexpert-session.store'))->open() }}
            @endif
            <div class="modal-body">
                <div class="row">
                        <div class="form-group col-md-12">
                            {{ html()->label(__('message.health_experts') . ' <span class="text-danger">*</span>', 'health_expert_id')->class('form-control-label')->attribute('escape', false) }}
                            {{ html()->select('health_expert_id')
                                    ->options(isset($id) ? [ $data->health_expert->id => optional($data->health_expert->users)->display_name] : [])
                                    ->value(old('health_expert_id'))
                                    ->class('select2js form-group mb-1')
                                    ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.health_experts')]))
                                    ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_health_expert', 'width' => 'expert_session']))
                                    ->attribute('data-allow-clear', 'true')
                                    ->disabled(isset($id))
                            }}
                            <span class="text-danger" id="ajax_form_validation_health_expert_id"></span>
                        </div>
                    <div class="form-group col-md-12">
                        {{  html()->label(__('message.week_days') . ' <span class="text-danger">*</span>', 'week_days')->class('form-control-label')->attribute('escape', false) }}

                        {{
                            html()->select('week_days[]')
                                ->options([
                                    1 => __('message.monday'),
                                    2 => __('message.tuesday'),
                                    3 => __('message.wednesday'),
                                    4 => __('message.thursday'),
                                    5 => __('message.friday'),
                                    6 => __('message.saturday'),
                                    7 => __('message.sunday'),
                                ])
                                ->multiple()
                                ->class('select2js form-group mb-1')
                                ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.week_days')]))
                                ->attribute('data-allow-clear', 'true')
                                ->value(old('week_days'))
                        }}
                        <span class="text-danger" id="ajax_form_validation_week_days"></span>
                    </div>
                    <div class="form-group col-md-6">
                        {{ html()->label(__('message.morning_start_time') . ' <span class="text-danger">*</span>', 'morning_start_time')->class('form-control-label')->attribute('escape', false) }}
                        {{ html()->text('morning_start_time')->value(old('morning_start_time'))->placeholder(__('message.morning_start_time'))->class('form-control mb-1 min-timepicker-session')->required() }}
                        <span class="text-danger" id="ajax_form_validation_morning_start_time"></span>
                    </div>
                    <div class="form-group col-md-6">
                        {{ html()->label(__('message.morning_end_time') . ' <span class="text-danger">*</span>', 'morning_end_time')->class('form-control-label')->attribute('escape', false) }}
                        {{ html()->text('morning_end_time')->value(old('morning_end_time'))->placeholder(__('message.morning_end_time'))->class('form-control mb-1 min-timepicker-session')->required() }}
                        <span class="text-danger" id="ajax_form_validation_morning_end_time"></span>
                    </div>

                <div class="form-group col-md-6">
                    {{ html()->label(__('message.evening_start_time') . ' <span class="text-danger">*</span>', 'evening_start_time')->class('form-control-label')->attribute('escape', false) }}
                    {{ html()->text('evening_start_time')->value(old('evening_start_time'))->placeholder(__('message.evening_start_time'))->class('form-control mb-1 min-timepicker-session')->required() }}
                    <span class="text-danger" id="ajax_form_validation_evening_start_time"></span>
                </div>

                <div class="form-group col-md-6">
                    {{ html()->label(__('message.evening_end_time') . ' <span class="text-danger">*</span>', 'evening_end_time')->class('form-control-label')->attribute('escape', false) }}
                    {{html()->text('evening_end_time')->value(old('evening_end_time'))->placeholder(__('message.evening_end_time'))->class('form-control mb-1 min-timepicker-session')->required()}}
                    <span class="text-danger" id="ajax_form_validation_evening_end_time"></span>
                </div>
            </div>
            <div class="modal-footer">
                {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right')->id('btn_submit')->attribute('data-form', 'ajax-submite-jquery-validation') }}
                <button type="button" class="btn btn-md btn-secondary float-right mr-1" data-dismiss="modal">{{ __('message.close') }}</button>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
<script>
    $('.select2js').select2({
        width: '100%',
    });
    flatpickr('.min-timepicker-session', {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
</script>
