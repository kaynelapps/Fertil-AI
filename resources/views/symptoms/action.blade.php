<?php
    $auth_user= authSession();
?>

@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('symptoms-edit'))
            <a class="mr-2" href="{{ route('symptoms.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('symptoms.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'symptoms'.$id)->open() }}
            @if($auth_user->can('symptoms-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="symptoms{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.symptoms') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.symptoms') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
@else
    @if($action_type == 'article_id' || $action_type == 'article_type')
        @if($query->article_id != null)
            <a class="mr-2" href="{{ route('article.show',$query->article_id) }}">{{ optional($query->article)->name }}</a>
        @else
            {{'-'}}
        @endif    
    @endif

    @if($action_type == 'action')
        <div class="d-flex justify-content-end align-items-center">
            @if($auth_user->can('symptoms-edit'))
                <a class="mr-2" href="{{ route('symptoms.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.symptoms') ]) }}"><i class="fas fa-edit text-primary"></i></a>
            @endif
            
            @if ($auth_user->can('symptoms-show'))
                <a class="mr-2" href="{{ route('symptoms.show', $id) }}" data-toggle="tooltip" title="{{ __('message.view_form_title',['form' => __('message.symptoms') ]) }}"><i class="fas fa-eye text-secondary"></i></a>
            @endif
            
            @if($auth_user->can('symptoms-delete'))
                {{ html()->form('DELETE', route('symptoms.destroy', $id))->attribute('data--submit', 'symptoms'.$id)->open() }}
                        <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="symptoms{{$id}}" data-toggle="tooltip"
                            data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.symptoms') ]) }}"
                            title="{{ __('message.delete_form_title',['form'=>  __('message.symptoms') ]) }}"
                            data-message='{{ __("message.delete_msg") }}'>
                            <i class="fas fa-trash-alt"></i>
                        </a>
                {{ html()->form()->close() }}
            @endif
        </div>
    @endif
@endif