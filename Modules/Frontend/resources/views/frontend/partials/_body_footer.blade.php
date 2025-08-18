@php
    $dummy_title = Dummydata('dummy_title');
    $dummy_desc = Dummydata('dummy_description');
    $playstoreUrl = SettingData('app-info', 'playstore_url') ?? 'javascript:void(0)';
    $appstoreUrl = SettingData('app-info', 'appstore_url') ?? 'javascript:void(0)';
@endphp

<footer class="mt-auto section-bg-color">
    <div class="container p-4 p-0">
        <div class="row gy-4 mt-2 mt-md-0">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ getSingleMediaSettingImage(getSettingFirstData('app-info', 'logo_image'), 'logo_image') }}" width="40" height="40" alt="Logo" class="me-2"></a>
                    <span class="fs-custom-22 line-height-30 site-color ms-2">{{ SettingData('app-info', 'app_name') }}</span>
                </div>
                <p class="second-text mb-3">{{ $app_settings->site_description ?? $dummy_desc }}</p>
                <p class="mb-3 d-flex align-items-center gap-2 second-text"><img src="{{ asset('frontend-section/images/letter.png') }}" alt="Email">{{ $app_settings->contact_email ?? $dummy_title }}</p>
                <p class="d-flex align-items-center gap-2 second-text"><img src="{{ asset('frontend-section/images/phone-calling.png') }}" alt="Phone">{{ $app_settings->contact_number ?? $dummy_title }}</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="{{ $app_settings->instagram_url ?? 'javascript:void(0)' }}"
                        {{ $app_settings->instagram_url != null ? 'target="_blank"' : '' }}>
                        <img src="{{ asset('frontend-section/images/insta.png') }}" width="30" height="30" alt="Instagram">
                    </a>
                    <a href="{{ $app_settings->facebook_url ?? 'javascript:void(0)' }}"
                        {{ $app_settings->facebook_url != null ? 'target="_blank"' : '' }}>
                        <img src="{{ asset('frontend-section/images/facebook.png') }}" width="30" height="30" alt="Facebook">
                    </a>
                    <a href="{{ $app_settings->twitter_url ?? 'javascript:void(0)' }}"
                        {{ $app_settings->twitter_url != null ? 'target="_blank"' : '' }}>
                        <img src="{{ asset('frontend-section/images/twitter.png') }}" width="30" height="30" alt="Twitter">
                    </a>
                    <a href="{{ $app_settings->linkedin_url ?? 'javascript:void(0)' }}"
                        {{ $app_settings->linkedin_url != null ? 'target="_blank"' : '' }}>
                        <img src="{{ asset('frontend-section/images/youtube.png') }}" width="30" height="30" alt="Linkedin">
                    </a>
                </div>
            </div>

            @if(count($calculator) > 0)
                <div class="col-lg-3 col-md-6 col-sm-6 mt-4">
                    <p class="fs-custom-18 second-text mb-4 mt-2">{{ __('frontend::message.calculators') }}</p>
                        <ul class="list-unstyled">
                            @foreach ($calculator as $value)
                                <li class="mt-3">
                                    <a class="text-decoration-none main-body-text" href="{{ route('calculator', ['slug' => $value->slug]) }}">
                                        {{ $value->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </ul>
                </div>
            @endif

            <div class="col-lg-2 col-md-6 col-sm-6 mt-4">
                <p class="fs-custom-18 second-text mt-2">{{ __('frontend::message.pages') }}</p>
                <ul class="list-unstyled mt-4">
                    <li><a href="{{ route('article.list') }}" class="text-decoration-none main-body-text">{{ __('frontend::message.articles') }}</a></li>
                    <li class="mt-3"><a href="{{ route('termofservice') }}" class="text-decoration-none main-body-text">{{ __('frontend::message.terms_conditions') }}</a></li>
                    <li class="mt-3"><a href="{{ route('privacypolicy') }}" class="text-decoration-none main-body-text">{{ __('frontend::message.privacy_policy') }}</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 mt-4">
                <p class="fs-custom-18 second-text mt-2">{{ __('frontend::message.experience') }} {{ SettingData('app-info', 'app_name') }} {{ __('frontend::message.app_on_mobile') }}</p>
                <div class="d-flex gap-2 align-items-center align-items-md-start mt-4">
                    <a class="me-2 mb-3" href="{{ $playstoreUrl }}"
                        {{ $playstoreUrl != 'javascript:void(0)' ? 'target="_blank"' : '' }}>
                        <img src="{{ asset('frontend-section/images/play-store.png') }}" alt="Google Play" class="img-fluid">
                    </a>
                    <a class="me-2 mb-3" href="{{ $appstoreUrl }}"
                        {{ $appstoreUrl != 'javascript:void(0)' ? 'target="_blank"' : '' }}>
                        <img src="{{ asset('frontend-section/images/app-store.png') }}" alt="App Store" class="img-fluid">
                    </a>
                </div>
                <div class="d-flex gap-3 mt-3">
                    @if(!empty($data['app-info']['playstore_image']))
                        <a href="{{ $playstoreUrl }}"
                            {{ $playstoreUrl != 'javascript:void(0)' ? 'target="_blank"' : '' }}>
                            <img src="{{ getSingleMediaSettingImage(getSettingFirstData('app-info', 'playstore_image'), 'playstore_image') }}" alt="Playstore" width="89">
                        </a>
                    @endif
                    @if(!empty($data['app-info']['appstore_image']))
                        <a href="{{ $appstoreUrl }}"
                            {{ $appstoreUrl != 'javascript:void(0)' ? 'target="_blank"' : '' }}>
                            <img src="{{ getSingleMediaSettingImage(getSettingFirstData('app-info', 'appstore_image'), 'appstore_image') }}"  alt="Appstore" width="89">
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <hr class="my-4 hr-line">
    <div class="text-center main-text pb-5">
        {{ $app_settings->site_copyright ?? $dummy_desc }}
    </div>
</footer>