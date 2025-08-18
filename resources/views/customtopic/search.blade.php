<x-master-layout :assets="$assets ?? []">
    <div>
    {{ html()->hidden('id', $id) }}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">{{ $pageTitle }}</h4>
                        <div class="float-right">
                            <a href="{{ route('customtopic-search.filter', ['id' => $id] + $params) }}" class="loadRemoteModel float-right btn btn-sm btn-orange m-2 mt-1 p-1">
                                <i class="fa fa-filter"></i> {{ __('message.filter') }}
                            </a>
                            <a href="{{ route('customtopic.show',$id) }}" class="float-right btn btn-sm btn-primary m-2 mt-1 p-1"><i class="fa fa-angle-double-left"></i> {{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.search'), 'search')->class('form-control-label') }}
                                    {{ html()->text('search')->id('search')->placeholder(__('message.search'))->class('form-control') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <div id="searchResults">
                            @include('customtopic.search-results', ['results' => $results, 'id' => $id])
                        </div>
                    </div>
                </div>

            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
    @section('bottom_script')
        <script>
            $(document).ready(function() {
                $('#search').on('keyup', function() {
                    let searchValue = $(this).val();
                    let id = "{{ $id }}";

                    $.ajax({
                        url: "{{ route('customtopic.search', $id) }}",
                        type: "GET",
                        data: { search: searchValue },
                        success: function(response) {
                            $('#searchResults').html(response.html);
                        }
                    });
                });

                $(document).on('click', '.add-topic-btn', function() {
                    var topicId = $(this).data('id');
                    var hiddenId = $('input[name="id"]').val();

                    $.ajax({
                        url: "{{ route('storeIds') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            topic_id: [topicId],
                            id: hiddenId
                        },
                        success: function(response) {
                            alert(response.message);
                        }
                    });
                });
            });
        </script>
    @endsection
</x-master-layout>
