<x-frontend-auth-layout>

    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card feedback-container">
                <div class="card-body">
                    <h5>{{ __('frontend::message.thank_you') }}</h5>
                  <p class="card-text">{{ __('frontend::message.your_feedback_has_been_received') }}</p>
                  <p class="card-text">{{ __('frontend::message.you_will_be_redirected_in') }} <span id="countdown">5</span> {{ __('frontend::message.seconds') }}.
                </p>
                </div>
            </div>
        </div>
    </div>

    @section('bottom_script')
    <script>
        $(document).ready(function() {
            var timeLeft = 5;

            var countdownTimer = setInterval(function() {
                timeLeft--;

                $('#countdown').text(timeLeft);

                if (timeLeft <= 0) {
                    clearInterval(countdownTimer);
                    window.location.href = "{{ route('browse') }}";
                }
            }, 1000);
        });
    </script>
    @endsection

</x-frontend-auth-layout>