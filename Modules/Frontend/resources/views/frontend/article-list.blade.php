<x-frontend-layout :assets="$assets ?? []">

    <section>
        <div class="container" id="article-container">
            @if (isset($tag))
                <h4 class="mb-2 mt-4 fs-custom-32 font-weight-500 line-height-46 text-center">{{ __('frontend::message.articles_on') }} {{ $tag->name }}</h4>
            @endif
            @include('frontend::frontend.article-items', ['articles' => $articles])
        </div>
        
        <div class="text-center mt-4">
            @if ($articles->hasMorePages())
                <button class="bg-transparent load-more-btn mb-3" id="load-more" data-next-page="{{ $articles->currentPage() + 1 }}" data-url="{{ request()->url() }}" data-last-page="{{ $articles->lastPage() }}">
                    {{ __('frontend::message.load_more') }}
                </button>
            @endif
        </div>
    </section>


    @section('bottom_script')

    <script>
         $(document).on('click', '#load-more', function () {
            var page = $(this).data('next-page');
            var url = $(this).data('url');            

            $.ajax({
                url: url + '?page=' + page,
                type: 'GET',
                success: (response) => {
                    $('#article-container').append(response);

                    let nextPage = page + 1;
                    let lastPage = $(this).data('last-page');
                    if (page < lastPage) {
                        $('#load-more').data('next-page', nextPage).text('Load More').prop('disabled', false);
                    } else {
                        $('#load-more').remove();
                    }
                }
            });
        });
    </script>
    @endsection

</x-frontend-layout>
