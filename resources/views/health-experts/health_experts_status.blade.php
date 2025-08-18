<div class="custom-switch custom-switch-text custom-switch-color custom-control-inline">
    <div class="custom-switch-inner">
        <input type="checkbox" class="custom-control-input bg-success access_change_status_{{$query->id}}" id="{{ $query->id }}" data-id="{{ $query->id }}" {{ $query->is_access ? 'checked' : '' }} value="{{ $query->id }}">
        <label class="custom-control-label" for="{{ $query->id }}" data-on-label="Yes" data-off-label="No"></label>
    </div>
</div>
<a href="{{route('access.password.form',['id' => $query->id])}}" id="access_pass_{{$query->id}}" class="float-right btn btn-sm btn-primary d-none loadRemoteModel">{{__('message.add_form_title',['form' => __('message.tags')])}}</a>
<meta name="user-role" content="{{ auth()->user()->user_type }}">

<script>
    $(document).ready(function() {
        function customConfirmation(message, callback) {
            const storageDark = localStorage.getItem('dark');
            const theme = (storageDark == "false") ? 'material' : 'dark';
            $.confirm({
                content: message,
                type: '',
                title: "{{ __('message.health_experts') }}",
                buttons: {
                    confirm: {
                        text: 'Yes',
                        action: function () {
                            callback(true);
                        }
                    },
                    cancel: {
                        text: 'No',
                        action: function () {
                            callback(false);
                        }
                    }
                },
                theme: theme
            });
        }
        var userRole = $('meta[name="user-role"]').attr('content');
        $('.access_change_status_{{$query->id}}').on('change', function() {
            var checkbox = $(this);
            var status = checkbox.is(':checked');

            if (status) {
                var confirmAction = 'Are you sure you want to enable access?';
                checkbox.prop('checked', false);
                customConfirmation(confirmAction, function(result) {     
                    if (result) {
                        $('#access_pass_{{$query->id}}').click();
                    } else {
                        checkbox.prop('checked', false);
                    }
                });
            } else {
                if (userRole == 'admin') {
                    var confirmAction = 'Are you sure you want to disable access?';
                    checkbox.prop('checked', true);
                    customConfirmation(confirmAction, function(result) {
                        if (result) {
                            checkbox.addClass("change_enable_health_experts");
                            checkbox.attr('data-type', 'health_experts');
                            checkbox.prop('checked', false);
                        } else {
                            checkbox.prop('checked', true);
                        }
                    });
                } else {
                    checkbox.prop('checked', true);
                    Snackbar.show({text: "{{ __('message.demo_permission_denied') }}", pos: 'bottom-center',backgroundColor: '#dc3545',actionTextColor: 'white'});

                }
            }
        });
    });
</script>
