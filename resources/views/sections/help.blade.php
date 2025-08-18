<!-- Modal -->
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
                <div class="form-group">
                    <div>
                        <h5>{{ __('message.help_section_title_one') }} <span class="font-weight-bold">"{{ __('message.help_form_title',['form' => __('message.info_section')] ) }}" </span>{{ __('message.help_section_title_two') }}</h5>
                    </div>
                    <div class="mt-2">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="">
                                    <li class="mt-3">{{ __('message.help_section_title_three') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ol class="ml-4">
                                    <li>{{ __('message.title') }}</li>
                                    <li>
                                        {{ __('message.goal_type') }} 
                                        <small class="text-muted">({{ __('message.only_goal_type_allowed') }}) <b>{{__('message.goal_type_example')}}</b></small>
                                    </li>
                                    <li>{{ __('message.category_id') }}</li>
                                    <li>{{ __('message.description') }}</li>
                                    <li>{{ __('message.status') }} 
                                        <small class="text-muted">({{  __('message.only_goal_type_allowed') }}) <b>{{__('message.status_example')}}</b></small>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="">
                                    <li class="mt-2">{{ __('message.help_section_title_four') }} <span class="font-weight-bold">"{{ __('message.help_form_title',['form' => __('message.info_section')]) }}" </span> {{ __('message.help_section_title_five') }}</li>
                                    <li class="mt-2">{{ __('message.help_section_title_one') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>                       
                </div>
            </div>
        </div>
    </div>
</div>