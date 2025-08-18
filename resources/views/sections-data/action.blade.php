<?php
    $auth_user= authSession();

?>
@if($topicData)
        <div class="d-flex justify-content-end align-items-center">
            @if($auth_user->can('sections-data-delete'))
                {{ html()->form('POST', route('topiciddestroy', ['id' => $id]))->style('display:inline;')->open() }}
                @csrf
                {{ html()->hidden('custome_id', $custome) }}
                {{ html()->button('<i class="fas fa-trash-alt" style="font-size:18px"></i>')->type('submit')->class('btn btn-link p-0 m-0')->attribute('title', __('message.restore_title')) }}
                {{ html()->form()->close() }}
            @endif
        </div>
    @else
    @if($deleted_at != null)
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('sections-edit'))
            <a class="mr-2" href="{{ route('sectiondata.restore', ['id' => $id ,'type'=>'restore']) }}" data--confirmation--restore="true" title="{{ __('message.restore_title') }}"><i class="ri-refresh-line" style="font-size:18px"></i></a>
        @endif
        {{ html()->form('DELETE', route('sectiondata.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'section_data'.$id)->open() }}
            @if($auth_user->can('sections-delete'))
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="section_data{{$id}}"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.section_data') ]) }}"
                    title="{{ __('message.force_delete_form_title',['form'=>  __('message.section_data') ]) }}"
                    data-message='{{ __("message.force_delete_msg") }}'>
                    <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                </a>
            @endif
        {{ html()->form()->close() }}
    </div>
    @else
    <div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('sections-data-edit'))
            <a class="mr-2" href="{{ route('section-data.edit', [$id,'section_data_main_category_id' => $section_data_main_category_id ]) }}" data-toggle="tooltip" title="{{ __('message.update_form_title',['form' => __('message.section_data') ]) }}"><i class="fas fa-edit text-primary"></i></a>
        @endif
        @if(auth()->user()->can('sections-delete'))
            {{ html()->form('DELETE', route('section-data.destroy', $id))->attribute('data--submit', 'section_data'.$id)->open() }}
                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="section_data{{$id}}" data-toggle="tooltip"
                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.section_data') ]) }}"
                    title="{{ __('message.delete_form_title',['form'=>  __('message.section_data') ]) }}"
                    data-message='{{ __("message.delete_msg") }}'>
                    <i class="fas fa-trash-alt"></i>
                </a>
            {{ html()->form()->close() }}
        @endif
    </div>
    @endif
@endif
