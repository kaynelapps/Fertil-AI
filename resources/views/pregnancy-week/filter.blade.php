<!-- Modal -->
<div class="modal-dialog modal-fullscreen-right" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{ html()->form('GET', route('pregnancy-week.index'))->id('filter-form')->open() }}
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group col-md-12">
                        {{ html()->label(__('message.week').' <span class="text-danger"></span>', 'week')->class('form-control-label') }}
                        {{ html()->select('weeks', isset($params['weeks']) ? [$params['weeks'] => $params['weeks'].' '.__('message.week')] : [])
                            ->value(old('week'))
                            ->class('select2js form-group')
                            ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.week')]))
                            ->attribute('data-ajax--url', route('ajax-list', ['type' => 'pregnancy_week']))
                            ->attribute('data-allow-clear', 'true')
                        }}
                    </div>
                    <div class="form-group col-md-12">
                        {{ html()->label(__('message.view_type') . ' <span class="text-danger">*</span>', 'view_type')->class('form-control-label') }}
                        {{ html()->select('view_type', ['' => ''] + getViewType(), $params['view_type'] ?? old($params['view_type'] ))
                            ->class('select2js form-group type')
                            ->id('view_type_id')
                            ->attribute('data-placeholder', __('message.select_name', [ 'select' => __('message.view_type') ]))
                            ->attribute('data-allow-clear', 'true')
                            ->required()
                        }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{ html()->submit(__('message.apply_filter'))->class('btn btn-primary btn-sm mr-2')->id('btn_submit') }}
                <a href="{{ route('pregnancy-week.index') }}" class="btn btn-orange btn-sm" title="{{ __('message.reset_filter') }}">
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
