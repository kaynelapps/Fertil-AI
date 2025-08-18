<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('uploadvideos-edit'))
            <a class="mr-2" href="{{ route('videosupload.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('videosupload.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'video'.$id)->open() }}
                @if($auth_user->can('uploadvideos-delete'))
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="video{{$id}}"
                        data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.video') ]) }}"
                        title="{{ __('message.force_delete_form_title',['form'=>  __('message.video') ]) }}"
                        data-message='{{ __("message.force_delete_msg") }}'>
                        <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                    </a>
                @endif
        {{ html()->form()->close() }}
    </div>
@else
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('uploadvideos-edit'))
            <a class="mr-2" href="{{ route('upload-videos.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.video') ]) }}"><i class="fas fa-edit text-primary"></i></a>
        @endif
        
        {{ html()->form('DELETE', route('upload-videos.destroy', $id))->attribute('data--submit', 'upload_videos'.$id)->open() }}
            @if($auth_user->can('uploadvideos-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="upload_videos{{$id}}" data-toggle="tooltip"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.video') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.video') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
@endif
