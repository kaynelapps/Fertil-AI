<!-- Modal -->
<div class="modal-dialog modal-fullscreen-right" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{ html()->form('GET', route('article.index'))->id('filter-form')->open() }}
            <div class="modal-body">
                <div class="form-group col-md-12">
                    {{ html()->label(__('message.goal_type'), 'goal_type')->class('form-control-label') }}
                    {{ html()->select('goal_type', ['' => ''] + getGoalType(), $params['goal_type'] ?? old($params['goal_type']))
                        ->class('select2js form-group type')
                        ->id('goal_type_id')
                        ->attribute('data-placeholder', __('message.select_name', [ 'select' => __('message.goal_type') ]))
                        ->attribute('data-allow-clear', 'true')
                    }}
                </div>
                <div class="form-group col-md-12">
                    {{ html()->label(__('message.article_use_for') . ' <span class="text-danger">*</span>', 'article_type')->class('form-control-label') }}
                    {{ html()->select('article_type', ['' => ''] + getArticleType(), $params['article_type'] ?? old($params['article_type']))->class('select2js form-group type')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article_use_for')]))->attribute('data-allow-clear', 'true') }}
                </div>
                <div class="form-group col-md-12">
                    {{ html()->label(__('message.type') . ' <span class="text-danger">*</span>', 'type')->class('form-control-label') }}
                    {{ html()->select('articles_type', ['' => '','free' => __('message.free'),'paid' => __('message.paid')], request('type') ?? old($params['articles_type']))->class('form-control select2js')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.type')]))->attribute('data-allow-clear', 'true') }}
                </div>
                <div class="form-group col-md-12">
                    {{ html()->label(__('message.sub_symptoms'), 'sub_symptoms_id')->class('form-control-label') }}
                    {{ html()->select('sub_symptoms_id',$params['sub_symptoms_id'] ? [$params['sub_symptoms_id'] => $params['subsymptom_name']] : old($params['subsymptom_name']),)
                        ->class('select2js form-group')
                        ->id('sub_symptoms_id')
                        ->attribute('data-placeholder', __('message.select_name', [ 'select' => __('message.sub_symptoms') ]))
                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'sub_symptoms_category']))
                        ->attribute('data-allow-clear', 'true')
                    }}
                </div>
                <div class="form-group col-md-12">
                    {{ html()->label(__('message.health_experts'), 'health_experts')->class('form-control-label') }}
                    {{ html()->select('expert_id',$params['expert_id'] ? [$params['expert_id'] => $params['expert_name']] : old($params['expert_id']),)
                        ->class('select2js form-group')
                        ->id('expert_id')
                        ->attribute('data-placeholder', __('message.select_name', [ 'select' => __('message.health_experts') ]))
                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_health_expert']))
                        ->attribute('data-allow-clear', 'true')
                    }}
                </div>
            </div>
            <div class="modal-footer">
                {{ html()->submit(__('message.apply_filter'))->class('btn btn-primary btn-sm mr-2')->id('btn_submit') }}
                <a href="{{ route('article.index') }}" class="btn btn-orange btn-sm" title="{{ __('message.reset_filter') }}">
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
