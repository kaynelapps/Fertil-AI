<?php
    $auth_user= authSession();
?>
@if($action_type == 'blog_link')
    @if($query->blog_link != null)
        <a class="mr-2" href="{{ route('article.show',$query->blog_link) }}">{{ optional($query->article)->name }}</a>
        {{-- 
            <a class="mr-2" href="{{ route('article.show',$query->blog_link) }}" data-toggle="tooltip" title="{{ optional($query->article)->name }}">{{ stringLong(optional($query->article)->name,'title') }}</a> 
        --}}
    @else
        {{'-'}}
    @endif    
@endif
@if($action_type == 'action')
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('calculatortool-edit'))
            <a class="mr-2" href="{{ route('calculator-tool.edit', $id) }}" title="{{ __('message.update_form_title',['form' => __('message.calculator_tool') ]) }}"><i class="fas fa-edit text-primary"></i></a>
        @endif
    </div>
@endif