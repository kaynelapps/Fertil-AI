<x-master-layout>
<div class="container-fluid">
    <div class="row">            
        <div class="col-lg-12">
            <div class="card card-block card-stretch">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                        <a href="{{ route('anonymoususer.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('message.back') }}</a>
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
                            <a href="{{ route('anonymoususer-view.show', $data->id) }}" class="nav-link {{ $type == 'detail' ? 'active': '' }}"> {{ __('message.profile') }} </a>
                        </li>
                       
                    </ul>
                </div>
            </div>
        </div>
    </div>            

    <div class="tab-content">
        <div class="row">
            @if( $type == 'detail' )
            <div class="col-lg-4">
                <div class="card card-block p-card">
                    <div class="profile-box">
                        <div class="profile-card rounded">
                            <img src="{{ $profileImage }}" alt="01.jpg" class="avatar-100 rounded d-block mx-auto img-fluid mb-3">
                            <h3 class="font-600 text-white text-center mb-0">{{ $data->display_name }}</h3>
                            <p class="text-white text-center mb-5">
                                @php
                                    $status = 'warning';
                                    switch ($data->status) {
                                        case 'active':
                                            $status = 'success';
                                            break;
                                        case 'inactive':
                                            $status = 'danger';
                                            break;
                                        case 'banned':
                                            $status = 'dark';
                                            break;
                                    }
                                @endphp
                                <span class="text-capitalize badge bg-{{ $status }} ">{{ $data->status }}</span>
                            </p>
                        </div>
                        <div class="pro-content rounded">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-icon mr-3"> 
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <p class="mb-0 eml">{{ auth()->user()->hasAnyRole(['admin', 'super_admin']) ? $data->email : maskSensitiveInfo('email', $data->email) }}</p>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-icon mr-3">
                                    <i class="fa fa-shapes"></i>
                                </div>
                                <p class="mb-0">
                                    {{ __('message.age') }} :
                                        <span style="display: inline-block; word-break: break-all;">
                                    {{ auth()->user()->hasAnyRole(['admin', 'super_admin']) ? ($data->age ?: 'N/A') : 'N/A' }}
                                    </span>
                                </p>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-icon mr-3">
                                    <i class="fa fa-code-branch"></i>
                                </div>
                                <p class="mb-0">{{ __('message.app_version') . ' : ' . (auth()->user()->hasAnyRole(['admin', 'super_admin']) ? ($data->app_version ?: '0') : '0') }}</p>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-icon mr-3">
                                    <i class="fa fa-podcast"></i>
                                </div>
                                <p class="mb-0">{{ __('message.app_source') . ' : ' . (auth()->user()->hasAnyRole(['admin', 'super_admin']) ? ($data->app_source ?: 'N/A') : 'N/A') }}</p>
                            </div>  
                             <div class="d-flex align-items-center mb-3">
                                <div class="p-icon mr-3">
                                    <i class="fa fa-globe"></i>
                                </div>
                                <p class="mb-0">
                                    {{ __('message.country') }} :
                                        <span style="display: inline-block; word-break: break-all;">
                                        {{ auth()->user()->hasAnyRole(['admin', 'super_admin']) ? ($data->country_name ?: 'N/A') : 'N/A' }}
                                    </span>
                                </p>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-icon mr-3">
                                    <i class="fa fa-city"></i>
                                </div>
                                <p class="mb-0">
                                    {{ __('message.city') }} :
                                        <span style="display: inline-block; word-break: break-all;">
                                        {{ auth()->user()->hasAnyRole(['admin', 'super_admin']) ? ($data->city ?: 'N/A') : 'N/A' }}
                                    </span>
                                </p>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-icon mr-3">
                                    <i class="fa fa-clock"></i>
                                </div>
                                <p class="mb-0">{{ __('message.last_active') . ' : ' . (auth()->user()->hasAnyRole(['admin', 'super_admin']) ? ($data->last_actived_at ? \Carbon\Carbon::parse($data->last_actived_at)->format('F d, Y g:i A') : 'N/A') : 'N/A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-lg-8">
            </div>
        </div>
    </div>    
</div>
</x-master-layout>