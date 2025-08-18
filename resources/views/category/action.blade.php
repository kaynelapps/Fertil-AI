<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('category-edit'))
            <a class="mr-2" href="{{ route('category.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('category.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'category'.$id)->open() }}
            @if($auth_user->can('category-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="category{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.category') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.category') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
@else
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('category-edit'))
            <a class="mr-2" href="{{ route('category.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.category') ]) }}"><i class="fas fa-edit text-primary"></i></a>
        @endif
        
        @if($auth_user->can('category-show'))
            <a class="mr-2" href="{{ route('category.show', $id) }}" data-toggle="tooltip" title="{{ __('message.view_form_title',['form' => __('message.category') ]) }}"><i class="fas fa-eye text-secondary"></i></a>
        @endif
        {{ html()->form('DELETE', route('category.destroy', $id))->attribute('data--submit', 'category'.$id)->open() }}
            @if($auth_user->can('category-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="category{{$id}}" data-toggle="tooltip"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.category') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.category') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
@endif