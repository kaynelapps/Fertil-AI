<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('faqs.update', $id))->attribute('enctype', 'multipart/form-data')->id('faq_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('faqs.store'))->attribute('enctype','multipart/form-data')->id('faq_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('faqs.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>', 'goal_type')->class('form-control-label') }}
                                    {{ html()->select('goal_type', ['' => ''] + getGoalType())->value($data->goal_type ?? old('goal_type'))->class('select2js form-group type')->id('goal_type_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))->attribute('data-allow-clear', 'true') }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.article'))->for('article_id')->class('form-control-label') }}
                                    {{ html()->select('article_id', isset($id) ? [optional($data->article)->id => optional($data->article)->name] : [])->value(old('article_id'))->class('select2js form-group')->id('article_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article']))->attribute('data-allow-clear', 'true') }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'),'0' => __('message.inactive'),])->value(old('status'))->class('form-control select2js')->required() }}
                                </div>

                                <div class="form-group col-md-12">
                                    {{ html()->label(__('message.question') . ' <span class="text-danger">*</span>', 'question')->class('form-control-label') }}
                                    {{ html()->textarea('question')->class('form-control')->placeholder(__('message.description'))->rows(5)->cols(40) }}
                                </div>

                                <div class="form-group col-md-12">
                                    {{ html()->label(__('message.answer') . ' <span class="text-danger">*</span>', 'answer')->class('form-control-label') }}
                                    {{ html()->textarea('answer')->class('form-control tinymce-description')->placeholder(__('message.answer'))->rows(3)->cols(40) }}
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
                formValidation("#faq_validation_form", {
                    goal_type: { required: true },
                    question: { required: true },
                }, {
                    goal_type: { required: "Please select a goal type."},
                    question: { required: "Please type a Question."},
                });
            });
        </script>
    @endsection
</x-master-layout>
