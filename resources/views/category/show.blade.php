<x-master-layout>
    <div class="container-fluid">
        <div class="row">            
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                         <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $data->name ?? '-' }}</h5>
                            <div class="float-right">
                                @if (auth()->user()->can('category-edit'))
                                    <a href="{{ route('category.edit', $id) }}"class="btn btn-sm btn-primary">{{ __('message.edit') }}</a>
                                @endif
                                <a href="{{ route('category.index') }}" class="btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('message.back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a href="{{ route('category.show',$data->id) }}" class="nav-link {{ $type == 'detail' ? 'active': '' }}"> {{ __('message.image_section') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('category.show', [ $data->id, 'type' => 'info_section']) }}" class="nav-link {{ $type == 'info_section' ? 'active': '' }}"> {{ __('message.info_section') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('category.show', [ $data->id, 'type' => 'common_question_answers']) }}" class="nav-link {{ $type == 'common_question_answers' ? 'active': '' }}"> {{ __('message.common_que_ans') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('category.show', [ $data->id, 'type' => 'section_data']) }}" class="nav-link {{ $type == 'section_data' ? 'active': '' }}"> {{ __('message.topic_data') }} </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @if( $type == 'section_data' )
                <div class="col-md-12">
                    <div class="accordion cursor" id="sectionList">
                        @if (isset($data) && isset($data->section_data_main) && count($data->section_data_main) > 0)
                            @foreach ($data->section_data_main as $index => $item)
                                <div class="card mb-2">
                                    <div class="card-header d-flex justify-content-between collapsed btn" id="heading_{{ $index }}" data-toggle="collapse" data-target="#section_{{ $index }}" aria-expanded="false" aria-controls="section_{{ $index }}">
                                        <div class="header-title">
                                            <h6 class="mb-0 text-capitalize"> <i class="fa fa-plus mr-10"></i> {{ ucfirst($item->title) ?? '' }}<span class="badge badge-secondary"></span></h6>
                                        </div>
                                    </div>
                                    <div id="section_{{ $index }}" class="collapse bg_light_gray" aria-labelledby="heading_{{ $index }}" data-parent="#sectionList">
                                        <div class="card-body table-responsive">
                                            @if (isset($item) && isset($item->section_data) && count($item->section_data) > 0)
                                                <table id="section_data_table_{{ $index }}" class="table text-center table-bordered bg_white">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('message.image') }}</th>
                                                            <th>{{ __('message.title') }}</th>
                                                            <th>{{ __('message.created_at') }}</th>
                                                            <th>{{ __('message.status') }}</th>
                                                            <th>{{ __('message.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($item->section_data as $data)
                                                            <tr>
                                                                <td class="pt-1 pb-1 text-center">
                                                                    <img src="{{ getSingleMedia($data, 'section_data_image') }}" alt="" width="40px" height="35px">
                                                                </td>
                                                                <td class="p-0">{{ $data->title }}</td>
                                                                <td class="p-0 text-center">
                                                                    {{ dateAgoFormate($data->created_at, true) }}
                                                                </td>
                                                                <td class="p-0">
                                                                    <span class="text-capitalize badge bg-{{ $data->status == 0 ? 'warning' : 'success-light' }}">{{ $data->status == 0 ? __('message.inactive') : __('message.active') }}</span>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex justify-content-center align-items-center">
                                                                        @if(auth()->user()->can('sections-edit'))
                                                                            <a class="mr-2" href="{{ route('section-data.edit', $data->id) }}" title="{{ __('message.update_form_title',['form' => __('message.section_data') ]) }}"><i class="fas fa-edit text-primary"></i></a>
                                                                        @endif

                                                                        @if(auth()->user()->can('sections-delete'))
                                                                            {{ html()->form('DELETE', route('section-data.destroy', $data->id))->attribute('data--submit', 'section_data'.$id)->open() }}
                                                                                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="section_data{{$id}}" data-toggle="tooltip"
                                                                                    data--confirmation='true' data-title="{{ __('message.delete_form_title',['form'=> __('message.section_data') ]) }}"
                                                                                    title="{{ __('message.delete_form_title',['form'=>  __('message.section_data') ]) }}"
                                                                                    data-message='{{ __("message.delete_msg") }}'>
                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                </a>
                                                                            {{ html()->form()->close() }}
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class="text-center">{{ __('message.no_record_found') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center">{{ __('message.no_record_found') }}</p>
                        @endif
                    </div>
                </div>
            @endif
            @if( $type == 'detail' )
                <div class="col-md-12">
                    <div class="card card-block">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title mb-0">{{ __('message.list_form_title', [ 'form' => __('message.image_section') ]) }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $dataTable->table(['class' => 'table  w-100'],false) }}
                        </div>
                    </div>
                </div>
            @endif
            @if( $type == 'info_section' )
                <div class="col-md-12">
                    <div class="card card-block">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title mb-0">{{ __('message.list_form_title', [ 'form' => __('message.info_section') ]) }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $dataTable->table(['class' => 'table  w-100'],false) }}
                        </div>
                    </div>
                </div>
            @endif
            @if( $type == 'common_question_answers' )
                <div class="col-md-12">
                    <div class="card card-block">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title mb-0">{{ __('message.list_form_title', [ 'form' => __('message.common_que_ans') ]) }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $dataTable->table(['class' => 'table  w-100'],false) }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @section('bottom_script')
      {{ !in_array($type,['section_data']) ? $dataTable->scripts() : '' }}
        <script>
            (function ($) {
                $('[id^=section_data_table_]').DataTable({
                    // Other options...
                    processing: true,
                    drawCallback: function() {
                        $('.dataTables_paginate > .pagination').addClass('justify-content-end mb-0');
                    },
                    initComplete: function() {
                        var table = this.api();
                        $('#dataTableBuilder_wrapper .dt-buttons button').removeClass('btn-secondary');
                        $('.studentattandance-list th:first-child').removeClass('sorting_asc');
                        $('th:eq('+table.order()[0][0]+')').removeClass('sorting_asc');
                    },
                    lengthMenu: [
                        [10, 50, 100, 500, -1], 
                        [10, 50, 100, 500,  "{{__('pagination.all')}}"]
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: "{{ __('pagination.search') }}",
                        lengthMenu : "{{  __('pagination.show'). ' _MENU_ ' .__('pagination.entries')}}",
                        zeroRecords: "{{__('pagination.no_records_found')}}",
                        info: "{{__('pagination.showing') .' _START_ '.__('pagination.to') .' _END_ ' . __('pagination.of').' _TOTAL_ ' . __('pagination.entries')}}", 
                        infoFiltered: "{{__('pagination.filtered_from_total') . ' _MAX_ ' . __('pagination.entries')}}",
                        infoEmpty: "{{__('pagination.showing_entries')}}",
                        paginate: {
                            previous: "{{__('pagination.__previous')}}",
                            next: "{{__('pagination.__next')}}"
                        }
                    },
                    
                    sDom: '<"row align-items-center"<"col-md-2"><"col-md-6" B><"col-md-4"f>><"table-responsive my-3" rt ><"d-flex"  <"flex-grow-1" l><"p-1" i><"mt-3 " p>><"clear">'
                });
            })(jQuery);

            (function($) {
                "use strict";
                $(document).ready(function(){
                    $(document).on('click','#sectionList .card-header',function(){
                        if($(this).find('i').hasClass('fa-minus')){
                            $('#sectionList .card-header i').removeClass('fa-plus').removeClass('fa-minus').addClass('fa-plus');
                            $(this).find('i').addClass('fa-plus').removeClass('fa-minus');
                        }else{
                            $('#sectionList .card-header i').removeClass('fa-plus').removeClass('fa-minus').addClass('fa-plus');
                            $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
                        }
                    });
                });
            })(jQuery);
        </script>
    @endsection
</x-master-layout>
