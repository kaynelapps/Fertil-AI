<!-- Modal -->
<div class="modal-dialog modal-fullscreen-right" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{ html()->form(route('faqs.index'))->method('GET')->id('filter-form')->open() }}
        <div class="modal-body">
            <div class="form-group">
                <div class="form-group col-md-12">
                    {{ html()->label(__('message.goal_type'))->for('goal_type')->class('form-control-label') }}
                    {{ html()->select('goal_type', ['' => ''] + getGoalType())->value($params['goal_type'] ?? old('goal_type'))->class('select2js form-group type')->id('goal_type_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))->attribute('data-allow-clear', 'true') }}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            {{ html()->submit(__('message.apply_filter'))->class('btn btn-primary btn-sm mr-2')->id('btn_submit') }}
            <a href="{{ route('faqs.index') }}" class="btn btn-orange btn-sm" title="{{ __('message.reset_filter') }}">
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
