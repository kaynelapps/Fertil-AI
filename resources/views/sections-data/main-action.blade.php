<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('sections-data-main-restore'))
                    <a class="mr-2" href="{{ route('section-data-main.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true"  data-toggle="tooltip" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('section-data-main.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'section_data'.$id)->open() }}
            <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="section_data{{$id}}"  data-toggle="tooltip"
                data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.section_data') ]) }}"
                title="{{ __('message.force_delete_form_title',['form'=>  __('message.section_data') ]) }}"
                data-message='{{ __("message.force_delete_msg") }}'>
                <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
            </a>
        {{ html()->form()->close() }}
    </div>
@else
<div class="d-flex justify-content-end align-items-center">
    @if($auth_user->can('sections-data-main-edit'))
    <a class="mr-2" href="{{ route('section-data-main.edit', $id) }}"  data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.section_data') ]) }}"><i class="fas fa-edit text-primary"></i></a>
    @endif
    
    @if($auth_user->can('sections-data-main-show'))
    <a class="mr-2" href="{{ route('section-data-main.show', $id) }}" data-toggle="tooltip" title="{{ __('message.view_form_title',['form' => __('message.section_data') ]) }}"><i class="fas fa-eye text-secondary"></i></a>
    @endif
    
    @if($auth_user->can('sections-data-main-delete'))
        {{ html()->form('DELETE', route('section-data-main.destroy', $id))->attribute('data--submit', 'section_data'.$id)->open() }}
            <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="section_data{{$id}}"  data-toggle="tooltip"
                data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.section_data') ]) }}"
                title="{{ __('message.delete_form_title',['form'=>  __('message.section_data') ]) }}"
                data-message='{{ __("message.delete_msg") }}'>
                <i class="fas fa-trash-alt"></i>
            </a>
        {{ html()->form()->close() }}   
    @endif
</div>
@endif