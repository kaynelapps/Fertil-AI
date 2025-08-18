<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                            <a href="{{ route('ask-expert.index') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-angle-double-left"></i> {{ __('message.back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card card-block">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>{{ __('message.user_details') }}</h4>
                            @if(optional($data->users)->status === 'active')
                                <span class="badge bg-success">{{ __('message.active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('message.inactive') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center" style="gap: 20px;">
                            <img src="{{ getSingleMedia($data->users, 'profile_image') }}" alt="User Image" class="rounded-circle" style="width: 80px; height: 80px;">
                            <div>
                                <h6><strong>{{ __('message.name') }}</strong>: {{ optional($data->users)->display_name }}</h6>
                                <h6><strong>{{ __('message.email') }}</strong>: {{ optional($data->users)->email }}</h6>
                                <h6><strong>{{ __('message.goal_type') }}</strong>: 
                                    @if(optional($data->user)->goal_type == 0)
                                        {{ __('message.track_pragnancy') }}
                                    @elseif(optional($data->user)->goal_type == 1)
                                        {{ __('message.track_cycle') }}
                                    @else
                                        {{ __('message.unknown') }}
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(optional($data->healthexpert)->users)
                <div class="col-md-6">
                    <div class="card card-block">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4>{{ __('message.health_expert_details') }}</h4>
                                @if(optional($data->healthexpert->users)->status === 'active')
                                    <span class="badge bg-success">{{ __('message.active') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('message.inactive') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center" style="gap: 20px;">
                                <img src="{{ getSingleMedia($data->healthexpert->users, 'profile_image') }}" alt="User Image" class="rounded-circle" style="width: 80px; height: 80px;">
                                <div>
                                    <h6><strong>{{ __('message.name') }}</strong>: {{ optional($data->healthexpert->users)->display_name }}</h6>
                                    <h6><strong>{{ __('message.email') }}</strong>: {{ optional($data->healthexpert->users)->email }}</h6>
                                    <h6><strong>{{ __('message.experience') }}</strong>: {{ optional($data->healthexpert)->experience }} {{ __('message.years') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-block">
                    <div class="card-body">
                        <h5 class="mb-3" style="border-bottom: 2px solid #007bff; padding-bottom: 8px;">{{ __('message.question') }}</h5>
                        <p class="text-muted" style="font-size: 16px;">{{ $data->description }}</p>

                        <h5 class="mt-4 mb-3" style="border-bottom: 2px solid #28a745; padding-bottom: 8px;">{{ __('message.answer') }}</h5>
                        @if(!empty($data->expert_answer))
                            <p class="text-dark" style="font-size: 16px; line-height: 1.6;">{{ $data->expert_answer }}</p>
                        @else
                            <p class="text-center text-muted" style="font-size: 16px; line-height: 1.6;"><b>{{ __('message.no_answer_from_doctor') }}</b></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-block">
                    <div class="card-body">
                        <h5 class="mb-3">{{__('message.images')}}</h5>
                        @if(getMediaFileExit($data, 'askexpert_image'))
                            @php
                                $images = $data->getMedia('askexpert_image');
                                $file_extensions = ['png', 'jpg', 'jpeg', 'gif', 'avif'];
                            @endphp
                            <div class="row">
                                @foreach($images as $image)
                                    @php
                                        $extension = pathinfo($image->getFullUrl(), PATHINFO_EXTENSION);
                                        $isValidExtension = $extension ? in_array(strtolower($extension), $file_extensions) : false;
                                    @endphp

                                    <div class="col-md-2 mb-4 text-center" id="image_preview_{{$image->id}}">
                                        <div class="position-relative">
                                            <a href="javascript:void(0);"class="image-popup-vertical-fit" data-image-url="{{ $image->getUrl() }}">
                                                @if($isValidExtension)
                                                    <img src="{{ $image->getUrl() }}" alt="{{ $image->name }}" class="img-fluid rounded image-popup-vertical-fit" style="height: 150px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('images/file.png') }}" class="img-fluid rounded" style="height: 150px; object-fit: cover;">
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>
