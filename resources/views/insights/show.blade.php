<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                            <div class="float-right">
                                <a href="{{ route('insights.index') }}" class="btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('message.back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-block">
                    <div class="card-header d-flex justify-content-between">
                        @php
                            switch ($data->status) {
                                case 0:
                                    $status_class = 'text-warning'; 
                                    $status_name = __('message.inactive');
                                    break;
                                case 1:
                                    $status_class = 'text-success';
                                    $status_name = __('message.active');
                                    break;
                                default:
                                    $status_class = 'text-secondary';
                                    $status_name = '-';
                            }
                        @endphp
                        <div class="header-title d-flex justify-content-between align-items-center w-100">
                            <h4 class="card-title mb-0">{{ $data->title }}</h4>
                            <h4 class="card-title mb-0"> <span class="{{ $status_class }}">{{ $status_name }}</span></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex align-items-start">
                            <div class="col-md-2">
                                <img src="{{ getSingleMedia($data, 'thumbnail_image') }}" alt="01.jpg" 
                                    class="rounded d-block img-fluid " 
                                    style="height: 170px; width: 75%;">
                            </div>
                            <div class="col-md-9">
                                <div class="row mb-4 mt-3">
                                    <div class="col-md-4">
                                        <h6 class="mt-2 mb-2"><b>{{ __('message.sub_symptoms') }}</b> : {{ optional($data->subSymptoms)->title }}</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mt-2 mb-2"><b>{{ __('message.goal_type') }}</b> : {{ getGoalType()[$data->goal_type] ?? '-' }}</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mt-2 mb-2"><b>{{ __('message.insights_use_for') }}</b> : {{ getArticleType()[$data->insights_type] ?? '-' }}</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6 class="mt-2 mb-2"><b>{{ __('message.view_type') }}</b> : {{ getViewType()[$data->view_type] ?? '-' }}</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mt-2 mb-2"><b>{{ __('message.created_at') }}</b> : {{ dateAgoFormate($data->created_at,true) }}</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mt-2 mb-2"><b>{{ __('message.updated_at') }}</b> : {{ dateAgoFormate($data->updated_at,true) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($data->view_type == 1)
            <div class="row">
                <div class="card card-block col-md-4">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title mb-0">{{ __('message.video') }}</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $video = $videos->getUrl();
                        @endphp

                        @if($video)
                            <video width="350" height="280" controls>
                                <source src="{{ $video }}" type="video/webm">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        @if($data->view_type == 0)
            <div class="row">
                <div class="card card-block">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title mb-0">{{ __('message.story_view') }}</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $images = $data->getMedia('story_image');
                            $file_extensions = config('constant.IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        @if (!empty($images) && count($images) > 0)
                            <div class="row">
                                @foreach($images as $image)
                                    @php
                                        $fileUrl = $image->getFullUrl();
                                        $imageExtension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION)); 
                                        $isImage = in_array($imageExtension, $file_extensions);
                                    @endphp
                                    <div class="col-md-1 pr-0 mb-3"> 
                                        <a href="{{ $isImage ? $image->getUrl() : asset('images/file.png') }}" class="magnific-popup-image-gallery avatar-100">
                                            <img id="{{ $image->id }}_preview" 
                                                src="{{ $isImage ? $image->getUrl() : asset('images/file.png') }}" 
                                                alt="{{ $image->name }}" 
                                                class="avatar-100 card-img-top">
                                        </a>
                                    </div>      
                                @endforeach
                            </div>  
                        @endif
                    </div>
                </div>
            </div>
        @endif
        @if($data->view_type == 2)
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-block mr-1 ml-1">
                                    <div class="card-body p-2 mr-2">
                                        <div class="card-header d-flex justify-content-between">
                                            <div class="header-title d-flex justify-content-between align-items-center w-100">
                                                <h4 class="card-title mb-0">{{__('message.detail_form_title',['form' => __('message.category')]) }}</h4>
                                            </div>
                                        </div>
                                        <table id="basic-table" class="table mb-3  text-center">
                                            <thead>
                                                <tr>    
                                                    <th scope='col'>{{ __('message.name') }}</th>
                                                    <th scope='col'>{{ __('message.goal_type') }}</th>
                                                    <th scope='col'>{{ __('message.header_image') }}</th>
                                                    <th scope='col'>{{ __('message.thumbnail_image') }}</th>
                                                    <th scope='col'>{{ __('message.created_at') }}</th>
                                                    <th scope='col'>{{ __('message.updated_at') }}</th>
                                                    <th scope='col'>{{ __('message.status') }}</th>
                                                </tr>
                                            </thead>
                                                @php
                                                    switch ($dataCategory->status) {
                                                        case 0:
                                                            $status_class = 'text-warning'; 
                                                            $status_name = __('message.inactive');
                                                            break;
                                                        case 1:
                                                            $status_class = 'text-success';
                                                            $status_name = __('message.active');
                                                            break;
                                                        default:
                                                            $status_class = 'text-secondary';
                                                            $status_name = '-';
                                                    }
                                                @endphp
                                            <tbody>
                                                <tr>
                                                    <td>{{ $dataCategory->name }}</td> 
                                                    <td>{{getGoalType()[$dataCategory->goal_type] ?? '-'}}</td> 
                                                    <td>
                                                        <img src="<?= getSingleMedia($dataCategory, 'header_image'); ?>" width="40" height="40">
                                                    </td>
                                                    <td>
                                                        <img src="<?= getSingleMedia($dataCategory, 'category_thumbnail_image'); ?>" width="40" height="40">
                                                    </td>
                                                    <td>{{ dateAgoFormate($dataCategory->created_at,true) }}</td>
                                                    <td>{{ dateAgoFormate($dataCategory->updated_at,true) }}</td>
                                                    <td>
                                                        <span class="{{ $status_class }}">{{ $status_name }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($data->article_id)
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-block">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title d-flex justify-content-between align-items-center w-100">
                                <h4 class="card-title mb-0">{{__('message.detail_form_title',['form' => __('message.article')]) }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row d-flex align-items-start">
                                <div class="col-md-6">
                                    <img src="{{ getSingleMedia($dataArticle, 'article_image') }}" alt="01.jpg" class="rounded d-block img-fluid mb-3 w-100 h-auto" style="max-height: 300px;">
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-block">
                                        <div class="card-header d-flex justify-content-between">
                                            <div class="header-title">
                                                <h4 class="card-title mb-0">{{ $dataArticle->name }}</h4>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="mt-2 mb-2">{{ __('message.health_experts') .' : '. optional($data->health_experts)->name }}</h6>
                                            <h6 class="mt-2 mb-2">{{ __('message.created_at') .' : '. dateAgoFormate($data->created_at,true) }}</h6>
                                            <div class="d-flex">{{ __('message.tags') .' : '. $selected_tags }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="card card-block">
                                            <div class="card-header d-flex justify-content-between">
                                                <div class="header-title">
                                                    <h4 class="card-title mb-0">{{ __('message.description') }}</h4>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                {!! htmlspecialchars_decode($dataArticle->description) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        @endif
    </div>
</x-master-layout>