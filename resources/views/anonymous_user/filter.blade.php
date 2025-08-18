<!-- Modal -->
<div class="modal-dialog modal-fullscreen-right" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{ html()->form('GET', route('anonymoususer.index'))->id('filter-form')->open() }}
        <div class="modal-body">
            <div class="form-group col-md-12 mb-2">
                {{ html()->label(__('message.goal_type'), 'goal_type')->class('form-control-label') }}
                {{ html()->select('goal_type', ['' => ''] + getGoalType(), $params['goal_type'] ?? old('goal_type'))->class('select2js form-group type')->id('goal_type_id')->attribute('data-placeholder', __('message.select_name', [ 'select' => __('message.goal_type') ]))->attribute('data-allow-clear', 'true') }}
            </div>
            <div class="form-group col-md-12 mb-2">
                {{ html()->label('App Version', 'app_version')->class('form-control-label') }}
                {{ html()->select('app_version', ['' => ''] + $filterData['app_versions']->mapWithKeys(fn($v) => [$v => $v])->toArray(), request('app_version'))->class('select2js form-group')->id('app_version')->attribute('data-placeholder', 'Select App Version')->attribute('data-allow-clear', 'true') }}
            </div>
            <div class="form-group col-md-12 mb-2 mb-2">
                {{ html()->label('App Source', 'app_source')->class('form-control-label') }}
                {{ html()->select('app_source', ['' => ''] + $filterData['app_sources']->mapWithKeys(fn($v) => [$v => $v])->toArray(), request('app_source'))->class('select2js form-group')->id('app_source')->attribute('data-placeholder', 'Select App Version')->attribute('data-allow-clear', 'true') }}
            </div>
            <div class="form-group col-md-12 mb-2">
                {{ html()->label('Region', 'region')->class('form-control-label') }}
                {{ html()->select('region', ['' => ''] + $filterData['regions']->mapWithKeys(fn($v) => [$v => $v])->toArray(), request('region'))->class('select2js form-group')->id('region')->attribute('data-placeholder', 'Select Region')->attribute('data-allow-clear', 'true') }}
            </div>

            <div class="form-group col-md-12 mb-2">
                {{ html()->label('Country', 'country_name')->class('form-control-label') }}
                {{ html()->select('country_name', ['' => ''] + $filterData['countries']->mapWithKeys(fn($v) => [$v => $v])->toArray(), request('country_name'))->class('select2js form-group')->id('country_name')->attribute('data-placeholder', 'Select Country')->attribute('data-allow-clear', 'true') }}
            </div>

            <div class="form-group col-md-12 mb-2">
                {{ html()->label('City', 'city')->class('form-control-label') }}
                {{ html()->select('city', ['' => ''] + $filterData['cities']->mapWithKeys(fn($v) => [$v => $v])->toArray(), request('city'))->class('select2js form-group')->id('city')->attribute('data-placeholder', 'Select City')->attribute('data-allow-clear', 'true') }}

            </div>

        </div>
        <div class="modal-footer">
            {{ html()->submit(__('message.apply_filter'))->class('btn btn-primary btn-sm mr-2')->id('btn_submit') }}
            <a href="{{ route('anonymoususer.index') }}" class="btn btn-orange btn-sm" title="{{ __('message.reset_filter') }}">
                <i class="ri-repeat-line"></i> {{ __('message.reset_filter') }}
            </a>
        </div>
        {{ html()->form()->close() }}
    </div>
</div>
<script>
     $('.select2js').select2({
        width: '100%',
    });

    $('#btn_submit').click(function() {
            $('#filter-form').submit(function() {
                $(this).find(':input').filter(function() {
                    return $.trim(this.value) === '';
                }).prop('disabled', true);

                return true;
            });
        });
</script>
