<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('healthexpertsession-edit'))
            <a class="mr-2" href="{{ route('helthsession.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('helthsession.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'health_expert_session'.$id)->open() }}
                @if($auth_user->can('healthexpertsession-delete'))
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="health_expert_session{{$id}}"
                        data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.health_expert_session') ]) }}"
                        title="{{ __('message.force_delete_form_title',['form'=>  __('message.health_expert_session') ]) }}"
                        data-message='{{ __("message.force_delete_msg") }}'>
                        <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                    </a>
                @endif
        {{ html()->form()->close() }}
    </div>
@else
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('healthexpertsession-edit'))
            <a class="mr-2 jqueryvalidationLoadRemoteModel" href="{{ route('healthexpert-session.edit', $id) }}"  title="{{ __('message.update_form_title',['form' => __('message.health_expert_session') ]) }}">
                <i class="fas fa-edit text-primary"></i>
            </a>
        @endif
        @if($auth_user->can('healthexpertsession-delete'))
            {{ html()->form('DELETE', route('healthexpert-session', $id))->attribute('data--submit', 'healthexpert-session'.$id)->open() }}
                <a class="mr-2 text-danger loadRemoteModel" href="javascript:void(0)" data--submit="healthexpert-session{{$id}}"  
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.health_expert_session') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.health_expert_session') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            {{ html()->form()->close() }}
        @endif
    </div>
@endif