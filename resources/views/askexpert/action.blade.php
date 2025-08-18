<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('askexpert-delete'))
            <a class="mr-2" href="{{ route('askexpert.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('askexpert.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'askexpert'.$id)->open() }}
            @if($auth_user->can('askexpert-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="askexpert{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.askexpert') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.askexpert') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
@else
    <div class="d-flex justify-content-end align-items-center">
        @if($hasImage)
            @if($auth_user->can('askexpert-image'))
                <a class="mr-2 jqueryvalidationLoadRemoteModel" href="{{ route('ask-expert.show', $id) }}" data-toggle="tooltip" title="{{ __('message.view_form_title',['form' => __('message.category') ]) }}"><i class="fa fa-images"></i></a>
            @endif
        @endif
        @if ($auth_user->can('article-show'))
            <a class="mr-2" href="{{ route('ask-expert.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.view_form_title',['form' => __('message.article') ]) }}"><i class="fas fa-eye text-secondary"></i></a>
        @endif
        <div class="d-flex justify-content-end align-items-center">
            @if($auth_user->can('askexpert-delete'))
                {{ html()->form('DELETE', route('ask-expert.destroy', $id))->attribute('data--submit', 'askexpert'.$id)->open() }}
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="askexpert{{$id}}" data-toggle="tooltip"
                        data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.askexpert') ]) }}"
                        title="{{ __('message.delete_form_title',['form'=>  __('message.askexpert') ]) }}"
                        data-message='{{ __("message.delete_msg") }}'>
                        <i class="fas fa-trash-alt"></i>
                    </a>
                {{ html()->form()->close() }}
            @endif
        </div>
    </div>
@endif
