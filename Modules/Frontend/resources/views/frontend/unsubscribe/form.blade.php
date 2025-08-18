<x-frontend-auth-layout>
  
    <div class="feedback-container">
        <p class="mb-3 font-weight-500">{{ __('frontend::message.if_you_have_a_moment_please_let_us_know') }}</p>
        <form action="{{ route('unsubscribe.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ encryptDecryptId($subscriber->email, 'encrypt') }}">
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="reason" id="emailFrequent" value="emailFrequent">
                <label class="form-check-label font-weight-500" for="emailFrequent">{{ __('frontend::message.you_email_too_frequently') }}</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="reason" id="neverSignedUp" value="neverSignedUp">
                <label class="form-check-label font-weight-500" for="neverSignedUp">{{ __('frontend::message.i_never_signed_up_for_your_emails') }}</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="reason" id="contentNotRelevant" value="contentNotRelevant">
                <label class="form-check-label font-weight-500" for="contentNotRelevant">{{ __('frontend::message.the_content_is_no_longer') }}</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="reason" id="contentNotExpected" value="contentNotExpected">
                <label class="form-check-label font-weight-500" for="contentNotExpected">{{ __('frontend::message.the_content_is_not_what_i_expected') }}</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="reason" id="other" value="other">
                <label class="form-check-label font-weight-500" for="other">{{ __('frontend::message.other') }}</label>
            </div>
            <div id="otherReason" class="form-group" style="display: none;">
                <label for="otherText">{{ __('frontend::message.could_you_tell_why_chose_this_option') }}</label>
                <textarea class="form-control" id="otherText" name="other_reason" rows="3" maxlength="1024"></textarea>
                <small id="characterCount" class="form-text text-muted">0 / 1024</small>
            </div>
            <button type="submit" class="btn mt-3 feedback-btn text-white">{{ __('frontend::message.submit_feedback') }}</button>
        </form>
    </div>


    @section('bottom_script')
        <script>
        $('input[name="reason"]').on('change', function() {
                if ($(this).val() === 'other') {
                    $('#otherReason').show();
                } else {
                    $('#otherReason').hide();
                    $('#otherText').val('');
                    $('#characterCount').text('0 / 1024');
                }
            });

            $('#otherText').on('input', function() {
                const count = $(this).val().length;
                $('#characterCount').text(`${count} / 1024`);
            });
        </script>
    @endsection
    
</x-frontend-auth-layout>