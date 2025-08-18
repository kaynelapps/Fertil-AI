<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{ html()->form('POST', route('access.password.store'))->open() }}  
        {{ html()->hidden('id', $health_id ?? null) }}
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group col-md-12">
                        {{ html()->label(__('message.password') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('password') }}
                        <div class="input-group">
                            {{ html()->password('password')->class('form-control')->placeholder(__('message.password'))->id('password')->attribute('autocomplete', 'off') }}
                            <div class="input-group-append">
                                <span class="input-group-text hide-show-password" style="cursor: pointer;">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right')->id('btn_submit')->attribute('data-form', 'ajax') }}
                <button type="button" class="btn btn-md btn-secondary float-right mr-1" data-dismiss="modal">{{ __('message.close') }}</button>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.hide-show-password').on('click', function() {
            var passwordInput = $('#password');
            var eyeIcon = $('.hide-show-password i');

            var passwordFieldType = passwordInput.attr('type');
            if (passwordFieldType === 'password') {
                passwordInput.attr('type', 'text');
                eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordInput.attr('type', 'password');
                eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    });
</script>