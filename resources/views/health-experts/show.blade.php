<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? $data->users->display_name }}</h5>
                            <a href="{{ route('health-experts.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('message.back') }}</a>
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
                                <a href="{{ route('health-experts.show', $data->id) }}" class="nav-link {{ $type == 'profile' ? 'active': '' }}"> {{ __('message.profile') }} </a>
                            </li>
                            @if( $data->is_access == 1)
                                <li class="nav-item">
                                    <a href="{{ route('health-experts.show', [ $data->id, 'type' => 'change_password']) }}" class="nav-link {{ $type == 'change_password' ? 'active': '' }}"> {{ __('message.change_password') }} </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('health-experts.show', [ $data->id, 'type' => 'blogs']) }}" class="nav-link {{ $type == 'blogs' ? 'active': '' }}"> {{ __('message.blog') .'s' }} </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="row">
                                @if( $type == 'profile' )
                                    <div class="col-lg-4">
                                        <div class="card card-block p-card">
                                            <div class="profile-box">
                                                <div class="profile-card rounded">
                                                    <img src="{{ $profileImage }}" alt="01.jpg" class="avatar-100 rounded d-block mx-auto img-fluid mb-3">
                                                    <h3 class="font-600 text-white text-center mb-0">{{ $data->users->first_name ?? '' }}</h3>
                                                    <div class="text-white text-center">
                                                        @php
                                                            $status = '';
                                                            $status_name = '';
                                                            $icon = '';

                                                            switch ($data->is_access) {
                                                                case '1':
                                                                    $status = 'light';
                                                                    $status_name = 'Access Enabled';
                                                                    $icon = 'fa-key';
                                                                    break;
                                                                case '0':
                                                                    $status = 'dark';
                                                                    $status_name = 'Access Disabled';
                                                                    $icon = 'fa-lock';
                                                                    break;
                                                            }
                                                        @endphp

                                                        <span class="text-capitalize badge bg-{{ $status }}">{{ $status_name }} <i class="fas {{ $icon }}"></i></span>
                                                    </div>
                                                </div>
                                                <div class="pro-content rounded">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="p-icon mr-3">
                                                            <i class="fas fa-envelope"></i>
                                                        </div>
                                                        <p class="mb-0 eml">{{ auth()->user()->hasRole('admin') ? $data->users->email : maskSensitiveInfo('email',$data->users->email) }}</p>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-3">

                                                        <p class="mb-0">{{ $data->tag_line ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="col-md-12">
                                            <div class="accordion cursor" id="description_list">
                                                @if (isset($data->short_description))
                                                    <div class="card mb-2">
                                                        <div class="card-header d-flex justify-content-between collapsed btn" id="heading_short_description" data-toggle="collapse" data-target="#section_short_description" aria-expanded="false" aria-controls="section_short_description">
                                                            <div class="header-title">
                                                                <h6 class="mb-0 text-capitalize"><i class="fa fa-plus mr-10"></i> {{ __('message.short_description') }}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                        <div id="section_short_description" class="collapse bg_light_gray" aria-labelledby="heading_short_description" data-parent="#description_list">
                                                            <div class="card-body table-responsive">
                                                                <h6 class="mb-0 text-capitalize">{!! $data->short_description ?? '' !!}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="accordion cursor" id="description_list_2">
                                                @if (isset($data->career))
                                                    <div class="card mb-2">
                                                        <div class="card-header d-flex justify-content-between collapsed btn" id="heading_career" data-toggle="collapse" data-target="#section_career" aria-expanded="false" aria-controls="section_career">
                                                            <div class="header-title">
                                                                <h6 class="mb-0 text-capitalize"><i class="fa fa-plus mr-10"></i> {{ __('message.career') }}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                        <div id="section_career" class="collapse bg_light_gray" aria-labelledby="heading_career" data-parent="#description_list_2">
                                                            <div class="card-body table-responsive">
                                                                <h6 class="mb-0 text-capitalize">{!! $data->career ?? '' !!}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="accordion cursor" id="description_list_3">
                                                @if (isset($data->education))
                                                    <div class="card mb-2">
                                                        <div class="card-header d-flex justify-content-between collapsed btn" id="heading_education" data-toggle="collapse" data-target="#section_education" aria-expanded="false" aria-controls="section_education">
                                                            <div class="header-title">
                                                                <h6 class="mb-0 text-capitalize"><i class="fa fa-plus mr-10"></i> {{ __('message.education') }}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                        <div id="section_education" class="collapse bg_light_gray" aria-labelledby="heading_education" data-parent="#description_list_2">
                                                            <div class="card-body table-responsive">
                                                                <h6 class="mb-0 text-capitalize">{!! $data->education ?? '' !!}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="accordion cursor" id="description_list_4">
                                                @if (isset($data->awards_achievements))
                                                    <div class="card mb-2">
                                                        <div class="card-header d-flex justify-content-between collapsed btn" id="heading_awards_achievements" data-toggle="collapse" data-target="#section_awards_achievements" aria-expanded="false" aria-controls="section_awards_achievements">
                                                            <div class="header-title">
                                                                <h6 class="mb-0 text-capitalize"><i class="fa fa-plus mr-10"></i> {{ __('message.awards_achievements') }}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                        <div id="section_awards_achievements" class="collapse bg_light_gray" aria-labelledby="heading_awards_achievements" data-parent="#description_list_2">
                                                            <div class="card-body table-responsive">
                                                                <h6 class="mb-0 text-capitalize">{!! $data->awards_achievements ?? '' !!}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="accordion cursor" id="description_list_5">
                                                @if (isset($data->area_expertise))
                                                    <div class="card mb-2">
                                                        <div class="card-header d-flex justify-content-between collapsed btn" id="heading_area_expertise" data-toggle="collapse" data-target="#section_area_expertise" aria-expanded="false" aria-controls="section_area_expertise">
                                                            <div class="header-title">
                                                                <h6 class="mb-0 text-capitalize"><i class="fa fa-plus mr-10"></i> {{ __('message.area_expertise') }}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                        <div id="section_area_expertise" class="collapse bg_light_gray" aria-labelledby="heading_area_expertise" data-parent="#description_list_2">
                                                            <div class="card-body table-responsive">
                                                                <h6 class="mb-0 text-capitalize">{!! $data->area_expertise ?? '' !!}<span class="badge badge-secondary"></span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if( $data->is_access == 1)
                                    @if( $type == 'change_password' )
                                        <div class="card card-block border-0">
                                            {{ html()->form('POST', route('access.password.store'))->open() }}
                                            @csrf
                                            {{ html()->hidden('id', $data->id ?? null) }}
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{ html()->label(__('message.new_password'))->class('form-control-label')->for('password') }}
                                                                {{ html()->password('password')->class('form-control')->placeholder(__('message.new_password'))->id('password')->attribute('autocomplete', 'new-password') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right')->id('btn_submit')->attribute('disabled', 'disabled') }}
                                                </div>
                                            {{ html()->form()->close() }}
                                        </div>
                                    @endif
                                @endif

                                @if( $type == 'blogs' )
                                    <div class="card card-block">
                                        <div class="card-body">
                                            {{ $dataTable->table(['class' => 'table  w-100'],   ) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('bottom_script')
     {{ in_array($type,['blogs',]) ? $dataTable->scripts() : '' }}
        <script>
            (function($) {
                // "use strict";
                $(document).ready(function(){
                    @if($type == 'blogs' || $type == 'educational_sessions')
                        $('.table').on('init.dt', function () {
                            $('.table').DataTable().column('expert_id:name').visible(false);
                        });
                    @endif
                    $(document).on('click', '.accordion .card-header', function(){
                        var $this = $(this);
                        var $icon = $this.find('i');
                        var isOpen = $icon.hasClass('fa-minus');
                        $('.accordion .card-header i').removeClass('fa-minus').addClass('fa-plus');

                        if (!isOpen) {
                            $icon.removeClass('fa-plus').addClass('fa-minus');
                        }
                    });

                    $('#password').on('input', function() {
                        var password = $(this).val();
                        if (password.length === 0) {
                            $('#btn_submit').prop('disabled', true);
                        } else {
                            $('#btn_submit').prop('disabled', false);
                        }
                    });

                    $('#btn_submit').on('click', function(e) {
                        e.preventDefault();

                        if (confirm('Are you sure you want to change the password?')) {
                            $(this).closest('form').submit();
                        }
                    });
                });
            })(jQuery);

        </script>
    @endsection
</x-master-layout>
