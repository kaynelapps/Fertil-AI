<?php
    $auth_user= authSession();
?>
@if($action_type == 'action')
    @if($deleted_at != null)
        <div class="d-flex justify-content-end align-items-center">
            @if($auth_user->can('health-experts-edit'))
                <a class="mr-2" href="{{ route('healthexpert.restore', ['id' => $id, 'type' => 'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}">
                    <i class="ri-refresh-line" style="font-size:18px"></i>
                </a>
            @endif
            {{ html()->form('DELETE', route('healthexpert.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'health_experts'.$id)->open() }}
                @if($auth_user->can('health-experts-delete'))
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="health_experts{{ $id }}"
                        data--confirmation='true'
                        data-title="{{ __('message.delete_form_title',['form'=> __('message.health_experts') ]) }}"
                        title="{{ __('message.force_delete_form_title',['form'=> __('message.health_experts') ]) }}"
                        data-message='{{ __("message.force_delete_msg") }}'>
                        <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                    </a>
                @endif
            {{ html()->form()->close() }}
        </div>
    @else
        <div class="d-flex justify-content-end align-items-center">
            @if($auth_user->can('health-experts-edit'))
                <a class="mr-2" href="{{ route('health-experts.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.health_experts') ]) }}"><i class="fas fa-edit text-primary"></i></a>
            @endif
            
            @if($auth_user->can('health-experts-show'))  
                <a class="mr-2" href="{{ route('health-experts.show', $id) }}" data-toggle="tooltip" title="{{ __('message.view_form_title',['form' => __('message.health_experts') ]) }}"><i class="fas fa-eye text-secondary"></i></a>
            @endif  
            @if($auth_user->can('health-experts-delete'))
                {{ html()->form('DELETE', route('health-experts.destroy', $id))->attribute('data--submit', 'health-experts'.$id)->open() }}
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="health-experts{{$id}}" data-toggle="tooltip"
                        data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.health_experts') ]) }}"
                        title="{{ __('message.delete_form_title',['form'=>  __('message.health_experts') ]) }}"
                        data-message='{{ __("message.delete_msg") }}'>
                        <i class="fas fa-trash-alt"></i>
                    </a>
                {{ html()->form()->close() }}   
            @endif
        </div>
    @endif
@endif