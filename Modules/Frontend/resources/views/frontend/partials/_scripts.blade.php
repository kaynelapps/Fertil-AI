<script src="{{ asset('frontend-section/js/jquery.min.js') }}"></script>
<script src="{{ asset('frontend-section/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend-section/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend-section/js/owl.carousel.js') }}"></script>

<script src="{{ asset('frontend-section/js/flatpickr.min.js') }}"></script>

@yield('bottom_script')

<script>
    $(document).ready(function () {
     
        $('.toast').each(function () {
            var toast = new bootstrap.Toast(this);
            toast.show();
        });

        $(".datepicker").flatpickr({
            dateFormat: "Y-m-d",
        });

        // START OWL CAROUSEL SECTION

         $("#article-slider").owlCarousel({
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },                
                980: {
                    items: 2
                },
                1199: {
                    items: 3
                },
            },
            dots: false,
            navigation: false,
            pagination: true,
            autoplay: true,
            margin: 20,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            rtl: $("html").attr("dir") === "rtl" ? true : false
        });

         $("#review-slider").owlCarousel({
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },                
                980: {
                    items: 3
                },
                1199: {
                    items: 5
                },
            },
            dots: true,
            navigation: false,
            pagination: true,
            autoplay: true,
            margin: 20,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            rtl: $("html").attr("dir") === "rtl" ? true : false
        });
        // END OWL CAROUSEL SECTION
    });
</script>
{{-- @include('helper.app_message') --}}
