<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
        {{ html()->modelForm($data, 'PATCH', route('pushnotification.update', $id))->attribute('enctype','multipart/form-data')->id('push_notifiction_validation_form')->open() }}
        @else
        {{ html()->form('POST',route('pushnotification.store'))->attribute('enctype', 'multipart/form-data')->id('push_notifiction_validation_form')->open()}}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('pushnotification.index') }} " class="btn btn-sm btn-primary"
                                role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label()->html(__('message.title') . ' <span class="text-danger">*</span>')->for('title')->class('form-control-label') }}
                                    {{ html()->text('title', old('title'))->placeholder(__('message.title'))->class('form-control') }}
                                </div>

                                <div class="form-group col-md-4">
                                    <label class="form-control-label" for="image">{{ __('message.image') }}</label>
                                    <div class="custom-file">
                                        {{ html()->file('notification_image')->class('custom-file-input')->id('notification_image')->attribute('data--target', 'notification_image_preview')->attribute('lang', 'en')->accept('image/*') }}
                                        <label class="custom-file-label">{{ __('message.choose_file', ['file' =>__('message.image')]) }}</label>
                                    </div>
                                    <span class="selected_file"></span>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <img id="notification_image_preview" src="{{ asset('images/default.png') }}"
                                        alt="image" class="attachment-image mt-1 notification_image_preview">
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label()->html(__('message.goal_type') . ' <span class="text-danger">*</span>')->for('goal_type')->class('form-control-label') }}

                                    {{ html()->select('goal_type', ['' => ''] + getGoalType())->value($data->type ?? old('goal_type'))->class('select2js form-group type')->id('goal_type_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))->attribute('data-allow-clear', 'true') }}
                                </div>
                                <div class="form-group col-md-4">
                                    {{ html()->label()->text(__('message.users'))->for('users')->class('form-control-label') }}
                                    {{ html()->select('user[]', [])->value(old('user'))->multiple()->class('select2js form-group')->id('notification_user_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.users')]))->attribute('data-allow-clear', 'true') }}
                                </div>

                                <div class="form-group col-md-2">
                                    <div class="custom-control custom-checkbox mt-4 pt-3">
                                        <input type="checkbox" class="custom-control-input selectAll" id="all_user"
                                            data-usertype="notification">
                                        <label class="custom-control-label" for="all_user">{{ __('message.select_all')
                                            }}</label>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    {{ html()->label()->html(__('message.message') . ' <span class="text-danger">*</span>')->for('message')->class('form-control-label') }}

                                    {{ html()->textarea('message')->class('form-control textarea')->rows(3)->placeholder(__('message.message')) }}
                                </div>
                            </div>
                            <hr>
                            {{ html()->submit(__('message.send'))->class('btn btn-md btn-primary float-right') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
    @section('bottom_script')
        <script>
            $(document).ready(function () {
                    formValidation("#push_notifiction_validation_form", {
                        title: {
                            required: true
                        }
                        , goal_type: {
                            required: true
                        }
                        , message: {
                            required: true
                        }
                        , "user[]": {
                            required: true
                        }
                        ,
                    }, {
                        title: {
                            required: "Please enter a Title."
                        }
                        , goal_type: {
                            required: "Please select a goal type."
                        }
                        , message: {
                            required: "Please enter a Message."
                        }
                        , "user[]": {
                            required: "Please enter a user."
                        }
                        ,
                    });
                });

        </script>
        <script>
            $(document).ready(function () {
                    $(".select2js").select2({
                        width: "100%"
                        ,
                    });

                    $(document).on('click', '.selectAll', function () {
                        var usertype = $(this).attr('data-usertype');
                        var userDropdown = $('#' + usertype + '_user_id');

                        if ($(this).is(':checked')) {
                            userDropdown.find('option').prop('selected', true);
                            userDropdown.trigger('change');
                            updateCounter(usertype);
                        } else {
                            userDropdown.val(null).trigger('change');
                            updateCounter(usertype);
                        }
                    });

                    function updateCounter(usertype) {
                        $('#' + usertype + '_user_id').next('span.select2').find('ul').html(function () {
                            let count = $('#' + usertype + '_user_id').select2('data').length;
                            return "<li class='ml-2'>" + count + " User Selected</li>";
                        });
                    }

                    $(document).on('change', '#goal_type_id', function () {
                        goal_type = $(this).val();
                        $('#notification_user_id').empty();
                        goalTypetList(goal_type);
                    });

                    function goalTypetList(goal_type) {
                        var goal_type_route = "{{ route('ajax-list', ['type' => 'get_users', 'goal_type' => '']) }}" + goal_type;
                        goal_type_route = goal_type_route.replace('amp;', '');

                        $.ajax({
                            url: goal_type_route
                            , success: function (result) {
                                $('#notification_user_id').select2({
                                    width: '100%'
                                    , placeholder: "{{ __('message.select_name', ['select' => __('message.users')]) }}"
                                    , data: result.results
                                });
                                $('.selectAll').prop("checked", false);
                            }
                        });
                    }
                });

        </script>
    @endsection
</x-master-layout>
