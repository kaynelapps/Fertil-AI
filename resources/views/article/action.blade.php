<?php
    $auth_user= authSession();
?>
@if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('article-edit'))
            <a class="mr-2" href="{{ route('article.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('article.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'article'.$id)->open() }}
            @if($auth_user->can('article-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="article{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.article') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.article') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
    {{ html()->form()->close() }}
    </div>
@else
    @if($action_type == 'health_expert')
        <a class="mr-2" href="{{ route('health-experts.show', [$article->expert_id , 'type' => 'blogs']) }}" data-toggle="tooltip" >{{ optional($article->health_experts->users)->display_name }}</a>
    @endif
    @if($action_type == 'action')
        <div class="d-flex justify-content-end align-items-center">
            @if($auth_user->can('article-edit'))
            <a class="mr-2" href="{{ route('article.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.article') ]) }}"><i class="fas fa-edit text-primary"></i></a>
            @endif
            @if ($auth_user->can('article-show'))
            <a class="mr-2" href="{{ route('article.show', $id) }}" data-toggle="tooltip" title="{{ __('message.view_form_title',['form' => __('message.article') ]) }}"><i class="fas fa-eye text-secondary"></i></a>
            @endif
            {{ html()->form('DELETE', route('article.destroy', $id))->attribute('data--submit', 'article'.$id)->open() }}
                @if($auth_user->can('article-delete'))
                    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="article{{$id}}" data-toggle="tooltip"
                        data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.article') ]) }}"
                        title="{{ __('message.delete_form_title',['form'=>  __('message.article') ]) }}"
                        data-message='{{ __("message.delete_msg") }}'>
                        <i class="fas fa-trash-alt"></i>
                    </a>
                @endif
            {{ html()->form()->close() }}
        </div>
    @endif
@endif

