<?php
    $auth_user= authSession();
?>
<div class="d-flex justify-content-end align-items-center">
    @if($auth_user->can('category-delete'))
        {{ html()->form('DELETE', route('cycle-dates-data.destroy', $id))->attribute('data--submit', 'cycle_date_data'.$id)->open() }}
            <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="cycle_date_data{{$id}}" data-toggle="tooltip"
                data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.cycle_dates_data') ]) }}"
                title="{{ __('message.delete_form_title',['form'=>  __('message.cycle_dates_data') ]) }}"
                data-message='{{ __("message.delete_msg") }}'>
                <i class="fas fa-trash-alt"></i>
            </a>
        {{ html()->form()->close() }}   
    @endif
</div>