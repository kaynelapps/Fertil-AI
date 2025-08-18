<div class="row">
    @if (count($articles) > 0)
        @foreach ($articles as $article)
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card border-0 radius-20 mb-4">
                    <a href="{{ route('article.detail', $article->slug) }}" class="text-decoration-none">
                        <img src="{{ getSingleMediaSettingImage($article->id != null ? $article : null, 'article_image', 'article_image') }}" class="mb-2 img-fluid w-100 radius-20 article-img" alt="Related Article">
                    </a>
                    <div>
                        <span class="heading-sub fs-custom-14">{{ __('frontend::message.published') }} </span>
                        <span class="site-base-color fs-custom-14">{{ date('d F Y', strtotime($article->created_at)) }}</span>
                        <p class="mb-0 second-text text-truncate">{{ $article->name ?? '' }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="d-flex justify-content-center flex-column align-items-center">
            <img src="{{ asset('frontend-section/images/no-data.png') }}" class="nodata-img radius-12">
        </div>
    @endif

</div>
