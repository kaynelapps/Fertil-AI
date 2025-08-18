<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('tags-edit'))
            <a class="mr-2" href="{{ route('tags.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('tags.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'tags'.$id)->open() }}
            @if($auth_user->can('tags-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="tags{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.tags') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.tags') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
         {{ html()->form()->close() }}
    </div>
@else
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('tags-edit'))
            <a class="mr-2 jqueryvalidationLoadRemoteModel" href="{{ route('tags.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.tags') ]) }}"><i class="fas fa-edit text-primary"></i></a>
        @endif
        
        {{ html()->form('DELETE', route('tags.destroy', $id))->attribute('data--submit', 'tags'.$id)->open() }}
            @if($auth_user->can('tags-delete'))
                <a class="mr-2 text-danger loadRemoteModel" href="javascript:void(0)" data--submit="tags{{$id}}" data-toggle="tooltip"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.tags') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.tags') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
@endif
