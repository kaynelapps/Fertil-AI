<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ ucfirst($data->title) }}</h5>
                            <div class="float-right">
                                @if(auth()->user()->can('symptoms-edit'))
                                    <a href="{{ route('symptoms.edit' ,$id) }}" class="btn btn-sm btn-primary mr-2">{{ __('message.edit') }}</a>
                                @endif
                                <a href="{{ route('symptoms.index') }}" class="btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('message.back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-block">
                    <div class="card-body">
                        <div class="d-flex p-2">
                            <h6 class="mr-2">Background Color : </h6>
                            <div style="width: 80px; height: 20px; background-color: {{ $data->bg_color }};"></div>
                        </div>
                        <div class="d-flex p-2">
                            <h6 class="mr-2">Background Color Code : </h6>
                            <div>{{ $data->bg_color }}</div>
                        </div>
                        <div class="d-flex p-2">
                            <h6 class="mr-2"> {{ __('message.goal_type') }} : </h6>
                            @php
                                if ($data->goal_type == 0) {
                                    $goal_type = __('message.track_cycle');
                                } elseif ($data->goal_type == 1) {
                                    $goal_type = __('message.get_pragnancy');
                                } elseif ($data->goal_type == 2) {
                                    $goal_type = __('message.track_pragnancy');
                                }    
                            @endphp
                            <div>{{ $goal_type ?? ' - ' }}</div>
                        </div>
                        <div class="d-flex p-2">
                            <h6 class="mr-2">Created at : </h6>
                            <h6 class="">{{ dateAgoFormate($data->created_at,true) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($data->article) && !empty($data->article))
            <div class="col-md-12">
                <div class="row">
                    <div class="card card-block">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title mb-0">{{ __('message.article') }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Health Expert</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data->article->name ?? ' - ' }}</td>
                                        <td>{{ $data->article->health_experts->users->display_name ?? ' - ' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-master-layout>
