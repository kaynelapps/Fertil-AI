<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('subscription-delete'))
            <a class="mr-2" href="{{ route('subscription.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('subscription.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'subscription'.$id)->open() }}
            @if($auth_user->can('subscription-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="subscription{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.subscription') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.subscription') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
         {{ html()->form()->close() }}
    </div>
@else
    @if($auth_user->can('subscription-delete'))
        {{ html()->form('DELETE', route('subscription.destroy', $id))->attribute('data--submit', 'subscription'.$id)->open() }}
            <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="subscription{{$id}}" data-toggle="tooltip"
                data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.subscription') ]) }}"
                title="{{ __('message.delete_form_title',['form'=>  __('message.subscription') ]) }}"
                data-message='{{ __("message.delete_msg") }}'>
                <i class="fas fa-trash-alt"></i>
            </a>
         {{ html()->form()->close() }}
    @endif
@endif
