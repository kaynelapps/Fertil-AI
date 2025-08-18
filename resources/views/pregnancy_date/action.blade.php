<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('pregnancydate-edit'))
            <a class="mr-2" href="{{ route('pregnancydate.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('pregnancydate.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'pregnancydate'.$id)->open() }}
            @if($auth_user->can('pregnancydate-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="pregnancydate{{$id}}"
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
        {{ html()->form('DELETE', route('pregnancy-date.destroy', $id))->attribute('data--submit', 'pregnancy-date'.$id)->open() }}
            <div class="d-flex justify-content-end align-items-center">
                @if($auth_user->can('pregnancydate-edit'))
                    <a class="mr-2" href="{{ route('pregnancy-date.edit', $id) }}" title="{{ __('message.update_form_title',['form' => __('message.pregnancy_date') ]) }}"><i class="fas fa-edit text-primary"></i></a>
                @endif

                @if($auth_user->can('pregnancydate-delete'))
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="pregnancy-date{{$id}}"
                        data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.pregnancy_date') ]) }}"
                        title="{{ __('message.delete_form_title',['form'=>  __('message.pregnancy_date') ]) }}"
                        data-message='{{ __("message.delete_msg") }}'>
                        <i class="fas fa-trash-alt"></i>
                    </a>
                @endif
            </div>
        {{ html()->form()->close() }}
    @endif
@endif
