<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('pregnancyweek-edit'))
            <a class="mr-2" href="{{ route('pregnancy-week.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('pregnancy-week.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'pregnancy-week'.$id)->open() }}
            @if($auth_user->can('pregnancyweek-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="pregnancy-week{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.pregnancy_date') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.pregnancy_date') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
@else
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('pregnancyweek-edit'))
            <a class="mr-2" href="{{ route('pregnancy-week.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.personalinsights') ]) }}"><i class="fas fa-edit text-primary"></i></a>
        @endif
        
        @if($auth_user->can('pregnancyweek-delete'))
            {{ html()->form('DELETE', route('pregnancy-week.destroy', $id))->attribute('data--submit', 'pregnancy-week'.$id)->open() }}
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="pregnancy-week{{$id}}" data-toggle="tooltip"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.pregnancy_date') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.pregnancy_date') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            {{ html()->form()->close() }}
        @endif
    </div>
@endif