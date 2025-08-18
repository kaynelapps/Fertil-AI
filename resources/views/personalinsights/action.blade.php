<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('personalinsights-edit'))
            <a class="mr-2" href="{{ route('personalinsights.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('personalinsights.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'personalinsights'.$id)->open() }}
                @if($auth_user->can('personalinsights-delete'))
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="personalinsights{{$id}}"
                        data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.personalinsights') ]) }}"
                        title="{{ __('message.force_delete_form_title',['form'=>  __('message.personalinsights') ]) }}"
                        data-message='{{ __("message.force_delete_msg") }}'>
                        <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                    </a>
                @endif
        {{ html()->form()->close() }}
    </div>
@else
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('personalinsights-edit'))
            <a class="mr-2" href="{{ route('personalinsights.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.personalinsights') ]) }}"><i class="fas fa-edit text-primary"></i></a>
        @endif
        
        @if($auth_user->can('personalinsights-delete'))
            {{ html()->form('DELETE', route('personalinsights.destroy', $id))->attribute('data--submit', 'personalinsights'.$id)->open() }}
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="personalinsights{{$id}}" data-toggle="tooltip"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.personalinsights') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.personalinsights') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            {{ html()->form()->close() }}
        @endif
    </div>
@endif