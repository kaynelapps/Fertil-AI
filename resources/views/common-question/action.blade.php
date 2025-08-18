<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('sections-edit'))
            <a class="mr-2" href="{{ route('commonquestion.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('commonquestion.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'commonquestion'.$id)->open() }}
                @if($auth_user->can('sections-delete'))
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="commonquestion{{$id}}"
                        data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.common_que_ans') ]) }}"
                        title="{{ __('message.force_delete_form_title',['form'=>  __('message.common_que_ans') ]) }}"
                        data-message='{{ __("message.force_delete_msg") }}'>
                        <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                    </a>
                @endif
        {{ html()->form()->close() }}
    </div>
@else
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('sections-edit'))
        <a class="mr-2" href="{{ route('common-question.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.common_que_ans') ]) }}"><i class="fas fa-edit text-primary"></i></a>
        @endif
        
        @if($auth_user->can('sections-delete'))
            {{ html()->form('DELETE', route('common-question.destroy', $id))->attribute('data--submit', 'common-question'.$id)->open() }}
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="common-question{{$id}}" data-toggle="tooltip"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.common_que_ans') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.common_que_ans') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            {{ html()->form()->close() }}   
        @endif
    </div>
@endif