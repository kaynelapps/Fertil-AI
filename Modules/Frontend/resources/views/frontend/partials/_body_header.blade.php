@php
    $playstoreUrl = SettingData('app-info', 'playstore_url') ?? 'javascript:void(0)';
@endphp
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('browse') }}">
            <img src="{{ getSingleMediaSettingImage(getSettingFirstData('app-info', 'logo_image'), 'logo_image') }}" width="40" height="40" alt="Logo">
            <strong class="font-weight-500 fs-custom-24 ms-3 main-text">{{ SettingData('app-info', 'app_name') }}</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fs-custom-18 line-height-24 {{ request()->is('/') || request()->routeIs('browse') ? 'active' : '' }}" href="{{ route('browse') }}">{{ __('frontend::message.home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-custom-18 line-height-24 {{ request()->is('article*') ? 'active' : '' }}" href="{{ route('article.list') }}">{{ __('frontend::message.articles') }}</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link fs-custom-18 line-height-24 {{ count($calculator) > 0 ? 'dropdown-toggle' : '' }}" href="javascript:void(0)"role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         {{ __('frontend::message.calculators') }}
                     </a>
                    @if(count($calculator) > 0)
                        <ul class="dropdown-menu">
                            @foreach ($calculator as $value)
                                <li class="d-flex align-items-center">
                                    <a class="dropdown-item pb-2 header-dropdown" href="{{ route('calculator', ['slug' => $value->slug]) }}">
                                        <img class="me-2" src="{{ getSingleMediaSettingImage($value?->id ? $value : null, 'calculator_thumbnail_image', 'calculator_thumbnail_image') }}" alt="Calculator Image" height="30" width="30">
                                        {{ $value->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            </ul>
        </div>
        <div class="d-none d-lg-block">
            <a href="{{ $playstoreUrl }}" class="btn btn-pink site-bg-color radius-40 font-weight-500 fs-custom-18 line-height-24 white-color" {{ $playstoreUrl != 'javascript:void(0)' ? 'target="_blank"' : '' }}">
                {{ __('frontend::message.try') }} {{ SettingData('app-info', 'app_name') }} {{ __('frontend::message.today') }}
            </a>
        </div>
    </div>
</nav>