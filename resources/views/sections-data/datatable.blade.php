<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                            <div class="float-right">
                                @if($auth_user->can('sections-data-main-add'))
                                    <a href="{{ route('section-data-main.create') }}" class="btn btn-sm btn-primary m-2 mt-1 p-1">
                                        <i class="fa fa-plus-circle"></i> {{ __('message.add_form_title', ['form' => __('message.topic')]) }}
                                    </a>
                                @endif
                                <a href="{{ url('export-selfcare') }}" class="btn btn-sm btn-info mr-2">
                                    <i class="fa fa-download"></i> {{ __('message.export') }}
                                </a>
                                <a href="{{route('bulk.selfcaredata')}}" class="btn btn-sm btn-primary mr-2">
                                    <i class="fas fa-file-import"></i> {{ __('message.import')}}
                                </a>
                                <a href="{{route('slefcare.filter',$params)}}" class="loadRemoteModel btn btn-sm btn-orange mr-2"><i class="fa fa-filter"></i>{{__('message.filter')}}</a>
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
                        <div class="tab-content">
                            <table id="basic-table" class="table mb-3 text-center">
                                <thead>
                                    <tr>
                                        <th scope='col'>{{ __('message.no') }}</th>
                                        <th></th>
                                        <th scope='col'>{{ __('message.title') }}</th>
                                        <th scope='col'>{{ __('message.category') }}</th>
                                        <th scope='col'>{{ __('message.goal_type') }}</th>
                                        <th scope='col'>{{ __('message.created_at') }}</th>
                                        <th scope='col'>{{ __('message.status') }}</th>
                                        <th scope='col'>{{ __('message.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sectionData as $value)
                                        @php
                                            $status = 'warning';
                                            $status_name = 'inactive';
                                            $view_name = '-';
                                            switch ($value->status) {
                                                case 0:
                                                    $status = 'warning';
                                                    $status_name = __('message.inactive');
                                                    break;
                                                case 1:
                                                    $status = 'success';
                                                    $status_name = __('message.active');
                                                    break;
                                            }
                                            switch ($value->goal_type) {
                                                case 0:
                                                    $view_name = __('message.track_cycle');
                                                    break;
                                                case 1:
                                                    $view_name = __('message.track_pragnancy');
                                                    break;
                                                default:
                                                    $view_name = '-'; 
                                                    break;
                                            }
                                            $deleted_at = $value->deleted_at;
                                            $id = $value->id;
                                        @endphp
                                        <tr data-id="{{ $value->id }}" class="{{ $value->deleted_at ? 'deleted-row' : '' }}">
                                            <td>{{ $loop->iteration }}</td> 
                                            <td>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                                    <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                                </svg>
                                            </td> 
                                            <td>{{ $value->title ?? '-'}}</td>
                                            <td>{{ ($value->category)->name ?? '-'}}</td>
                                            <td>{{ $view_name }}</td>
                                            <td>{{ dateAgoFormate($value->created_at,true) ?? '-'}}</td>
                                            <td><span class="badge bg-{{ $status }}">{{ $status_name }}</span></td>
                                            <td>
                                                @if($deleted_at != null)
                                                    <div class="d-flex justify-content-end align-items-center">
                                                        @if($auth_user->can('sections-data-main-restore'))
                                                            <a class="mr-2" href="{{ route('section-data-main.restore', ['id' => $id , 'type' => 'restore']) }}" data-toggle="tooltip" title="{{ __('message.restore_title') }}">
                                                                <i class="ri-refresh-line" style="font-size:18px"></i>
                                                            </a>
                                                        @endif
                                                        {{ html()->form('DELETE', route('section-data-main.force.delete', ['id' => $id, 'type' => 'forcedelete']))->attribute('data--submit', 'section_data'.$id)->open() }}
                                                            <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="section_data{{$id}}" data-toggle="tooltip"
                                                                data--confirmation="true" data-title="{{ __('message.delete_form_title', ['form' => __('message.section_data')]) }}"
                                                                title="{{ __('message.force_delete_form_title', ['form' => __('message.section_data')]) }}"
                                                                data-message="{{ __('message.force_delete_msg') }}">
                                                                <i class="ri-delete-bin-2-fill" style="font-size:18px"></i>
                                                            </a>
                                                        {{ html()->form()->close() }}
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-end align-items-center">
                                                        @if($auth_user->can('sections-data-main-edit'))
                                                            <a class="mr-2" href="{{ route('section-data-main.edit', $id) }}" data-toggle="tooltip" title="{{ __('message.update_form_title', ['form' => __('message.section_data')]) }}">
                                                                <i class="fas fa-edit text-primary"></i>
                                                            </a>
                                                        @endif
                                                        
                                                        @if($auth_user->can('sections-data-main-show'))
                                                            <a class="mr-2" href="{{ route('section-data-main.show', $id) }}" data-toggle="tooltip" title="{{ __('message.view_form_title', ['form' => __('message.section_data')]) }}">
                                                                <i class="fas fa-eye text-secondary"></i>
                                                            </a>
                                                        @endif
                                                        
                                                        @if($auth_user->can('sections-data-main-delete'))
                                                            {{ html()->form('DELETE', route('section-data-main.destroy', $id))->attribute('data--submit', 'section_data'.$id)->open() }}
                                                                <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="section_data{{$id}}" data-toggle="tooltip"
                                                                    data--confirmation="true" data-title="{{ __('message.delete_form_title', ['form' => __('message.section_data')]) }}"
                                                                    title="{{ __('message.delete_form_title', ['form' => __('message.section_data')]) }}"
                                                                    data-message="{{ __('message.delete_msg') }}">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </a>
                                                            {{ html()->form()->close() }}   
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('bottom_script')
        <script>
            $("#basic-table").DataTable({
                "dom":  '<"row align-items-center"<"col-md-2"><"col-md-6" B><"col-md-4"f>><"table-responsive my-3" rt><"d-flex" <"flex-grow-1" l><"p-2" i><"mt-4" p>><"clear">',
                "order": [[0, "asc"]]
            });
            
            $('#basic-table tbody').sortable({
                items: 'tr',
                cursor: 'move',
                helper: function (e, ui) {
                    ui.children().each(function () {
                        $(this).width($(this).width());
                    });

                    return ui;
                },
                update: function (event, ui) {
                    var rows = [];
                    var pageInfo = $('#basic-table').DataTable().page.info(); 
                    var startPosition = pageInfo.start; 

                    $('#basic-table tbody tr').each(function (index) {
                        var currentPosition = $(this).data('index'); 
                        var newPosition = startPosition + index; 

                        if (currentPosition !== newPosition) {
                            rows.push({
                                id: $(this).data('id'), 
                                newPosition: newPosition, 
                            });
                        }
                    });
                    if (rows.length > 0) {
                        $.ajax({
                            url: '{{ route("saveDragondrop") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                reorderedData: rows,
                            },
                            success: function (response) {
                                console.log('Rows reordered successfully:', response);
                            },
                            error: function (xhr, status, error) {
                                console.error('Failed to update row order:', error, xhr.responseText);
                            },
                        });
                    } else {
                        console.log('No rows to update (positions unchanged).');
                    }
                }
            });
        </script>
    @endsection
</x-master-layout>
