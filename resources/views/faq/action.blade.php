<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('faq-edit'))
            <a class="mr-2" href="{{ route('faqs.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('faqs.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'faq'.$id)->open() }}
            @if($auth_user->can('faq-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="faq{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.faq') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.faq') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
@else
    {{ html()->form('DELETE', route('faqs.destroy', $id))->attribute('data--submit', 'faqs'.$id)->open() }}
        <div class="d-flex justify-content-end align-items-center">
            @if($auth_user->can('faq-edit'))
                <a class="mr-2" href="{{ route('faqs.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.faq') ]) }}"><i class="fas fa-edit text-primary"></i></a>
            @endif

            @if($auth_user->can('faq-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="faqs{{$id}}" data-toggle="tooltip"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.faq') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.faq') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            @endif
        </div>
    {{ html()->form()->close() }}
@endif
