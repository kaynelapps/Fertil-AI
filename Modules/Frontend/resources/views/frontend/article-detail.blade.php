<x-frontend-layout :assets="$assets ?? []">

    <div class="py-5 site-sub-color">
        <div class="container-fluid">
            <div class="container">
                <div>
                    <ol class="breadcrumb mb-0">
                        <li><a class="text-decoration-none second-text" href="{{ route('browse') }}">{{ __('frontend::message.home') }}</a>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 4.16669L12.5 10L7.5 15.8334" stroke="#2C2C2C" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </li>
                        <li><a class="text-decoration-none second-text" href="{{ route('article.list') }}">{{ __('frontend::message.articles') }}</a>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 4.16669L12.5 10L7.5 15.8334" stroke="#2C2C2C" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </li>
                        <li class="second-text">   
                            {{ $data['article']->goal_type == 0 ? __('message.track_cycle') : __('message.track_pragnancy') }}
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 4.16669L12.5 10L7.5 15.8334" stroke="#2C2C2C" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </li>
                        <li class="active second-text" aria-current="page">{{ __('frontend::message.detail_page') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-4 fs-custom-32 font-weight-500 line-height-46">{{ $data['article']->name }}</h4>
                <img src="{{ getSingleMediaSettingImage($data['article']->id != null ? $data['article'] : null, 'article_image', 'article_image') }}" alt="Article Img" class="img-fluid article-detail-image radius-20 w-100 mb-3">
                <div class="mb-3">
                    <span class="heading-sub">{{ __('frontend::message.published') }} </span>
                    <span class="site-base-color">{{ date('d F Y', strtotime($data['article']->created_at)) }}</span> |
                    <span class="heading-sub">{{ __('frontend::message.written_by') }} </span> 
                    <span class="site-base-color">{{ optional($data['article']->health_experts->users)->display_name }}</span>
                </div>
                <div class="mb-3">
                    <span class="heading-sub me-2">{{ __('message.tags') }}:</span>
                    <div class="d-inline-flex flex-wrap gap-2">
                        @foreach($selected_tags as $tag)
                            <a href="{{ route('articles.by.tag', $tag->slug) }}" class="px-3 py-1 radius-8 secondary-color site-color text-decoration-none">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div>{!! $data['article']->description !!}</div>
            </div>
    
            <!-- Related Articles -->
            <div class="col-lg-4">
                <div class="card p-3 border-0 section-bg-color radius-20">
                    <h6 class="mb-3 fs-custom-22 line-height-30">{{ __('frontend::message.related_articles') }}</h6>
                    <div class="d-flex flex-column gap-3">
                        @foreach ($data['related_articles'] as $article)
                            <a href="{{ route('article.detail', $article->slug) }}" class="text-decoration-none">
                                <div class="d-flex align-items-start gap-3 mb-2">
                                    <img src="{{ getSingleMediaSettingImage($article->id != null ? $article : null, 'article_image', 'article_image') }}" class="rounded-3 flex-shrink-0 related-article-img" alt="Related Article">
                                    <div>
                                        <p class="mb-0 second-text multiline-truncate">{{ $article->name ?? '' }}</p>
                                        <div>
                                            <span class="heading-sub">{{ __('frontend::message.published') }} </span>
                                            <span class="site-base-color">{{ date('d F Y', strtotime($data['article']->created_at)) }}</span>
                                        </div>
                                        <div>
                                            <span class="heading-sub">{{ __('frontend::message.written_by') }} </span> 
                                            <span class="site-base-color">{{ optional($data['article']->health_experts->users)->display_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-frontend-layout>
