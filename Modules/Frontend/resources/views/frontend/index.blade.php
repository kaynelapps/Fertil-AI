<x-frontend-layout :assets="$assets ?? []">

    <main>
        <section class="py-5">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-lg-6 mb-4 mb-lg-0 text-center text-lg-start py-lg-5 py-0">
                        <h2 class="heading-color display-5 display-md-3 display-lg-2 font-weight-500 line-height-52">{{ $data['app-info']['title'] }}</h2>
                            <p class="heading-sub fs-custom-18 line-height-24 mt-3">
                                {{ $data['app-info']['description'] }}
                            </p>
                        <div class="d-flex flex-wrap justify-content-center justify-content-lg-start mt-4 gap-4 align-items-center">
                            @if ((!empty($data['app-info']['playstore_url']['url']) && $data['app-info']['playstore_url']['url'] !== 'javascript:void(0)') || (!empty($data['app-info']['appstore_url']['url']) && $data['app-info']['appstore_url']['url'] !== 'javascript:void(0)'))
                                <div class="d-flex flex-column align-items-center gap-3">
                                    @if (!empty($data['app-info']['playstore_url']['url']) && $data['app-info']['playstore_url']['url'] !== 'javascript:void(0)')
                                        <a href="{{ $data['app-info']['playstore_url']['url'] }}" {{ $data['app-info']['playstore_url']['target'] ?? '' }}>
                                            <img class="social-icon" src="{{ asset('frontend-section/images/play-store.png') }}" alt="Google Play" height="45">
                                        </a>
                                    @endif

                                    @if (!empty($data['app-info']['appstore_url']['url']) && $data['app-info']['appstore_url']['url'] !== 'javascript:void(0)')
                                        <a href="{{ $data['app-info']['appstore_url']['url'] }}" {{ $data['app-info']['appstore_url']['target'] ?? '' }}>
                                            <img class="social-icon" src="{{ asset('frontend-section/images/app-store.png') }}" alt="App Store" height="45">
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <div class="d-flex gap-3">
                                @if(!empty($data['app-info']['appstore_image']))
                                    <a href="{{ $data['app-info']['appstore_url']['url'] }}" {{ $data['app-info']['appstore_url']['target'] }} class="text-decoration-none ">
                                        <img src="{{ $data['app-info']['appstore_image'] }}" class="social-qr-icon" alt="App QR">
                                    </a>
                                @endif
                                @if(!empty($data['app-info']['playstore_image']))
                                    <a href="{{ $data['app-info']['playstore_url']['url'] }}" {{ $data['app-info']['playstore_url']['target'] }} class="text-decoration-none">
                                        <img src="{{ $data['app-info']['playstore_image'] }}" class="social-qr-icon" alt="Play QR">
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 text-center">
                        <div class="position-relative d-inline-block">
                            @if(!empty($data['app-info']['image']))
                                <img src="{{ $data['app-info']['image'] }}" alt="App Preview" class="img-fluid hero-img">
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </section>
        
        <!-- START APP SECTION -->

        @foreach($data['sections'] as $index => $section)
            <section class="py-5 {{ $index % 2 == 0 ? 'section-bg-color' : '' }}">
                <div class="container">
                    <div class="row align-items-center py-5">

                        @if($index % 2 == 0)
                            {{-- Even index: Image left, Text right --}}
                            <div class="col-lg-6 mb-4 mb-lg-0 text-md-center text-lg-start">
                                @if(getSingleMediaSettingImage($section, 'section_image'))
                                    <img src="{{ getSingleMediaSettingImage($section, 'section_image') }}" class="img-fluid app-image" alt="App">
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <h2 class="display-5 display-md-3 display-lg-2 mb-3 font-weight-500">{{ $section->title }}</h2>
                                <p class="text-muted mb-4 fs-custom-18">{{ $section->subtitle }}</p>
                                <ul class="list-unstyled">
                                    @foreach($section->websitesectiontitles as $item)
                                        <li class="mb-3 d-flex align-items-end">
                                            <span class="text-danger me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <rect x="0.75" y="0.75" width="18.5" height="18.5" rx="9.25" stroke="#FF5783" stroke-width="1.5"></rect>
                                                    <path d="M9.71468 4.87812C9.80449 4.60172 10.1955 4.60172 10.2853 4.87812L11.2797 7.9386C11.3199 8.06221 11.4351 8.1459 11.565 8.1459H14.783C15.0737 8.1459 15.1945 8.51778 14.9594 8.6886L12.356 10.5801C12.2508 10.6565 12.2068 10.7919 12.247 10.9155L13.2414 13.976C13.3312 14.2524 13.0149 14.4822 12.7797 14.3114L10.1763 12.4199C10.0712 12.3435 9.92881 12.3435 9.82366 12.4199L7.22026 14.3114C6.98514 14.4822 6.6688 14.2524 6.75861 13.976L7.75302 10.9155C7.79318 10.7919 7.74918 10.6565 7.64404 10.5801L5.04063 8.6886C4.80552 8.51778 4.92635 8.1459 5.21697 8.1459H8.43495C8.56492 8.1459 8.68011 8.06221 8.72027 7.9386L9.71468 4.87812Z" fill="#FF5783"></path>
                                                </svg>
                                            </span>
                                            <span class="main-text fs-custom-18">{{ $item->title }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        @else
                            {{-- Odd index: Text left, Image right --}}
                            <div class="col-lg-6">
                                <h2 class="display-5 display-md-3 display-lg-2 mb-3 font-weight-500">{{ $section->title }}</h2>
                                <p class="text-muted mb-4 fs-custom-18">{{ $section->subtitle }}</p>
                                <ul class="list-unstyled">
                                    @foreach($section->websitesectiontitles as $item)
                                        <li class="mb-3 d-flex align-items-end">
                                            <span class="text-danger me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <rect x="0.75" y="0.75" width="18.5" height="18.5" rx="9.25" stroke="#FF5783" stroke-width="1.5"></rect>
                                                    <path d="M9.71468 4.87812C9.80449 4.60172 10.1955 4.60172 10.2853 4.87812L11.2797 7.9386C11.3199 8.06221 11.4351 8.1459 11.565 8.1459H14.783C15.0737 8.1459 15.1945 8.51778 14.9594 8.6886L12.356 10.5801C12.2508 10.6565 12.2068 10.7919 12.247 10.9155L13.2414 13.976C13.3312 14.2524 13.0149 14.4822 12.7797 14.3114L10.1763 12.4199C10.0712 12.3435 9.92881 12.3435 9.82366 12.4199L7.22026 14.3114C6.98514 14.4822 6.6688 14.2524 6.75861 13.976L7.75302 10.9155C7.79318 10.7919 7.74918 10.6565 7.64404 10.5801L5.04063 8.6886C4.80552 8.51778 4.92635 8.1459 5.21697 8.1459H8.43495C8.56492 8.1459 8.68011 8.06221 8.72027 7.9386L9.71468 4.87812Z" fill="#FF5783"></path>
                                                </svg>
                                            </span>
                                            <span class="main-text fs-custom-18">{{ $item->title }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-lg-6 mb-4 mb-lg-0 text-md-center text-lg-start">
                                @if(getSingleMediaSettingImage($section, 'section_image'))
                                    <img src="{{ getSingleMediaSettingImage($section, 'section_image') }}" class="img-fluid app-image" alt="App">
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endforeach
        <!-- END APP SECTION -->

        <!-- START ARTICLES SECTION -->

        <section>
            <div class="container-fluid py-5">
                <div class="container py-3">
                    <h3 class="display-5 display-md-3 display-lg-2 m-0 font-weight-500 line-height-52 text-center">{{ __('frontend::message.articles') }}</h3>
                    <div class="d-flex justify-content-end align-items-center flex-wrap gap-3">
                        @if(count($data['article']) > 0)
                            <a href="{{ route('article.list') }}" role="button" class="btn white-color site-bg-color fs-6 lh-sm fw-medium rounded-pill px-4 py-2">
                            {{ __('frontend::message.view_all') }}
                            </a>
                        @endif
                    </div>
        
                    <div id="article-slider" class="owl-carousel mt-3">
                        @foreach($data['article']->isNotEmpty() ? $data['article'] : [(object) ['id' => '', 'goal_type' => '', 'title' => $data['dummy_title']]] as $article)
                            <div class="card card-radius border-0 mt-2 g-4">
                                <a href="{{ $article->id != null ? route('article.detail', $article->slug) : 'javascript:void(0)' }}" class="text-decoration-none">
                                    <img src="{{ getSingleMediaSettingImage($article->id != null ? $article : null, 'article_image', 'article_image') }}" class="article-img w-100 img-fluid" alt="Periods Tracking">
                                    <div class="card-body">
                                        <span class="heading-sub fs-custom-14">{{ __('frontend::message.published') }} </span>
                                        <span class="site-base-color fs-custom-14">{{ date('d F Y', strtotime($article->created_at ?? '')) }}</span>
                                        <p class="card-text second-text fs-18 multiline-truncate">{{ $article->name ?? '' }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        
        <!-- END ARTICLES SECTION -->

        {{-- START REVIEW SECTION --}}

        <section class="section-bg-color py-4">
            <div class="container py-5">
                <h2 class="display-5 display-md-3 display-lg-2 font-weight-500 line-height-52 text-center">{{ $data['user-review']['title'] }}</h2>
                <p class="main-body-text fs-custom-8 line-height-24 mt-3 text-center">{{ $data['user-review']['subtitle'] }}</p>
                <div id="review-slider" class="owl-carousel mt-5">
                    @foreach ($data['review']->isNotEmpty() ? $data['review'] : [(object) ['id' => '', 'title' => $data['dummy_title'], 'subtitle' => $data['dummy_title'], 'description' => $data['dummy_description']]] as $user_review)
                        <div class="testimonial-card d-flex justify-content-between flex-column radius-16 p-3 bg-white">
                            <div>
                                <div class="d-flex justify-content-center mb-1">
                                    <div class="profile-icon mb-2 d-flex justify-content-center align-items-center fs-custom-24 site-sub-color">{{ strtoupper(substr($user_review->title, 0, 1)) }}</div>
                                </div>
                                <p class="testimonial-text m-0">
                                    {{ $user_review->description }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center mt-1">
                                <div class="profile-icon-small me-3 d-flex justify-content-center align-items-center fs-custom-18 site-sub-color">
                                    {{ strtoupper(substr($user_review->title, 0, 1)) }}
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fs-custom-18 font-weight-500 text-truncate max-width-150">
                                        {{ $user_review->title }}
                                    </div>
                                    <div class="main-text text-truncate max-width-150">
                                        {{ $user_review->subtitle }}
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    @endforeach
                </div>
              </div>
        </section>

        {{-- END REVIEW SECTION --}}

        {{-- START VIDEO SECTION --}}

        @if (!empty($data['app-info']['video_url']) && $data['app-info']['video_url'] !== 'javascript:void(0)')
            <section class="py-5">
                <div class="container my-4">
                    @php
                        $isEmbed = Str::contains($data['app-info']['video_url'], ['youtu.be', 'youtube.com', 'vimeo.com']);
                    @endphp

                    <div class="container my-4">
                        @if ($isEmbed)
                            <div>
                                <iframe src="{{ convertToEmbedUrl($data['app-info']['video_url']) }}" title="Embedded Video" allowfullscreen class="radius-20 w-100 custom-video"></iframe>
                            </div>
                        @else
                            <video controls class="w-100 radius-20">
                                <source src="{{ $data['app-info']['video_url'] }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- END VIDEO SECTION --}}

        <!-- START RATING SECTION -->

        {{-- <section class="py-5">
            <div class="mx-auto py-5 px-3 position-relative text-center rating-heading">
                <img src="{{ asset('frontend-section/images/01.png') }}" alt="" class="position-absolute top-50 start-0 translate-middle-y d-none d-md-block">
                <div>
                    {!! renderStars($data['rating']['rating_star'] ?? 0) !!}
                    <h3 class="m-0 fs-4 fs-md-3 fs-lg-2 fw-semibold lh-sm mt-4 main-text">
                        {{ $data['rating']['title'] }}
                    </h3>
                </div>
        
                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center mt-4 gap-3">
                    <div class="d-flex align-items-center gap-1">
                        <img src="{{ asset('frontend-section/images/apple.png') }}" alt="apple" />
                        <div>{{ $data['rating']['appstore_review'] }}</div>
                        <div>/5</div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <img src="{{ asset('frontend-section/images/playstore.png') }}" alt="playstore" />
                        <div>{{ $data['rating']['playstore_review'] }}</div>
                        <div>/5</div>
                    </div>
                </div>
        
                <img src="{{ asset('frontend-section/images/2.png') }}" alt="" class="position-absolute top-50 end-0 translate-middle-y d-none d-md-block" />
            </div>
        </section> --}}

        <!-- END RATING SECTION -->

        <!-- START NEWSLETTER SECTION -->
        
        {{-- <section class="px-sm-0 px-2 mb-5">
            <div class="newsletter radius-20 section-bg-color container mx-auto py-5 mt-5 overflow-hidden">
                <h3 class="m-0 text-center newsletter-heading mx-auto font-weight-500 line-height-52">{{ $data['newsletter']['title'] }}</h3>
                <form action="{{ route('subscribe') }}" method="POST" class="p-2 mx-auto" autocomplete="off">
                    @csrf
                    <div class="search mt-5 d-flex justify-content-between p-2 rounded-pill">
                        <input type="email" name="email" class="form-control custom-input border-0 bg-transparent" placeholder="{{ __('frontend::message.email') }}" required>
                        <button type="submit" class="text-white border-0 radius-30 newsletter-btn site-bg-color">{{ __('frontend::message.subscribe') }}</button>
                    </div>
                </form>
            </div>
        </section> --}}
        <!-- END NEWSLETTER SECTION -->

    </main>

    @section('bottom_script')
        <script>

        $(document).ready(function () {
            $(".read-more").on("click", function() {
                var $this = $(this);
                var $card = $this.closest(".review-card");
                var $moreText = $card.find(".more-text");
                var $dots = $card.find(".dots");

                $moreText.toggleClass("d-none");
                $dots.toggle();

                if ($moreText.hasClass("d-none")) {
                    $this.text("Read more");
                } else {
                    $this.text("Read less");
                }
            });
        });
        </script>
    @endsection

</x-frontend-layout>
