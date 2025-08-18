<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('sub-symptoms-edit'))
            <a class="mr-2" href="{{ route('subsymptoms.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('subsymptoms.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'subsymptoms'.$id)->open() }}
            @if($auth_user->can('sub-symptoms-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="subsymptoms{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.sub_symptoms') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.sub_symptoms') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
         {{ html()->form()->close() }}
    </div>
@else
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('sub-symptoms-edit'))
            <a class="mr-2" href="{{ route('sub-symptoms.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.sub_symptoms') ]) }}"><i class="fas fa-edit text-primary"></i></a>
        @endif
        
        @if($auth_user->can('sub-symptoms-delete'))
            {{ html()->form('DELETE', route('sub-symptoms.destroy', $id))->attribute('data--submit', 'sub_symptoms'.$id)->open() }}
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="sub_symptoms{{$id}}" data-toggle="tooltip"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.sub_symptoms') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.sub_symptoms') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            {{ html()->form()->close() }}
        @endif
    </div>
@endif