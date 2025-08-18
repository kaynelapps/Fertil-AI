<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('imagesection-edit'))
            <a class="mr-2" href="{{ route('imagesection.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('imagesection.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'image-section'.$id)->open() }}
            @if($auth_user->can('imagesection-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="image-section{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.pregnancy_date') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.pregnancy_date') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
@else
    @if($action_type == 'article_id')
        @if($query->article_id != null)
            <a class="mr-2" href="{{ route('article.show',$query->article_id) }}">{{ optional($query->article)->name }}</a>
        @else
            {{'-'}}
        @endif    
    @endif
    @if($action_type == 'action')
        <div class="d-flex justify-content-end align-items-center">
            @if($auth_user->can('imagesection-edit'))
            <a class="mr-2" href="{{ route('image-section.edit', $id) }}" title="{{ __('message.update_form_title',['form' => __('message.pregnancy_date') ]) }}"><i class="fas fa-edit text-primary"></i></a>
            @endif
            
            @if($auth_user->can('imagesection-delete'))
                {{ html()->form('DELETE', route('image-section.destroy', $id))->attribute('data--submit', 'image-section'.$id)->open() }}
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="image-section{{$id}}" 
                        data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.pregnancy_date') ]) }}"
                        title="{{ __('message.delete_form_title',['form'=>  __('message.pregnancy_date') ]) }}"
                        data-message='{{ __("message.delete_msg") }}'>
                        <i class="fas fa-trash-alt"></i>
                    </a>
                {{ html()->form()->close() }}
            @endif
        </div>
    @endif
@endif