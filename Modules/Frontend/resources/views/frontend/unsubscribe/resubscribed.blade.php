<x-frontend-auth-layout>

    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="card feedback-container">
                <div class="card-body">
                    <h5>{{ __('frontend::message.thank_you') }}</h5>
                  <p class="card-text mb-3">{{ __('frontend::message.you_have_been_successfully_removed_from_this_subscriber_list') }}</p>
                  <p class="mb-3">{{ __('frontend::message.did_you_unsubscribe_by_accident') }} <a href="{{ route('resubscribe', ['email' => encryptDecryptId($email,'encrypt')]) }}">{{ __('frontend::message.resubscribe') }}</a>
                </div>
            </div>
        </div>
    </div>

</x-frontend-auth-layout>