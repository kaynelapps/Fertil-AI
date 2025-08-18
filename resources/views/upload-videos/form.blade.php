<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('upload-videos.update', $id))->attribute('enctype', 'multipart/form-data')->id('video_upload_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('upload-videos.store'))->attribute('enctype','multipart/form-data')->id('video_upload_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('upload-videos.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label()->html(__('message.title') . ' <span class="text-danger">*</span>')->for('title')->class('form-control-label')  }}
                                    {{ html()->text('title', old('title'))->placeholder(__('message.title'))->class('form-control')  }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label()->html(__('message.video_duration') . ' <span class="text-danger">*</span>')->for('video_duration')->class('form-control-label')  }}
                                    {{ html()->text('video_duration',old('video_duration'))->placeholder(__('message.video_duration'))->class('form-control')  }}
                                </div>

                                <div class="form-group col-md-{{ isset($id) && getMediaFileExit($data, 'upload_video_thumbnail_image') ? 4 : 6 }}">
                                    <label class="form-control-label" for="image">{{ __('message.thumbnail_image') }} </label>
                                    <div class="custom-file">
                                        <input type="file" name="upload_video_thumbnail_image" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                    </div>
                                </div>

                                @if( isset($id) && getMediaFileExit($data, 'upload_video_thumbnail_image'))
                                    <div class="col-md-2 mb-2">
                                        <img id="upload_video_thumbnail_image_preview" src="{{ getSingleMedia($data,'upload_video_thumbnail_image') }}" alt="upload_video_thumbnail_image-image" class="attachment-image mt-1">
                                        <a class="text-danger remove-file ml-1" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'upload_video_thumbnail_image']) }}"
                                            data--submit='confirm_form'
                                            data--confirmation='true'
                                            data--ajax='true'
                                            data-toggle='tooltip'
                                            title='{{ __("message.remove_file_title" , ["name" =>  __("message.image") ]) }}'
                                            data-title='{{ __("message.remove_file_title" , ["name" =>  __("message.image") ]) }}'
                                            data-message='{{ __("message.remove_file_msg") }}'>
                                            <i class="ri-close-circle-line"></i>
                                        </a>
                                    </div>
                                @endif
                                <div class="form-group col-md-6">
                                    <label class="form-control-label" for="image">{{ __('message.video') }} </label>
                                    <div class="custom-file">
                                        <input type="file" name="videos_upload" class="custom-file-input" accept="video/*" id="videoUpload">
                                        <label class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.video') ]) }}</label>
                                    </div>
                                    <!-- Error message container -->
                                    <div id="error-message" style="color: red; display: none;">{{ __('message.file_size_error') }}</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label()->html(__('message.status') . ' <span class="text-danger">*</span>')->for('status')->class('form-control-label')  }}
                                    {{ html()->select('status', [    '1' => __('message.active'),    '0' => __('message.inactive')])->value(old('status'))->class('form-control select2js')->required()  }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12" id="video_upload">
                                    @if( isset($id) && getMediaFileExit($data, 'videos_upload'))
                                        <div class="col-md-2 mb-2 position-relative">
                                            <?php
                                                $file_extention = config('constant.VIDEO_EXTENSIONS');
                                                $video = getSingleMedia($data, 'videos_upload');
                                                $extention = in_array(strtolower(videoExtension($video)), $file_extention);
                                                $path = parse_url($video, PHP_URL_PATH);
                                                $file_name = basename($path);
                                            ?>

                                            @if($extention)
                                                <video width="350" height="280" controls>
                                                    <source src="{{ $video}}" type="video/mp4">
                                                </video>
                                            @else
                                                <video width="350" height="280" controls>
                                                    <source src="{{ $video}}" type="video/mp4">
                                                </video>
                                            @endif
                                            <a class="text-danger remove-file"
                                                href="{{ route('remove.file', ['id' => $data->id, 'type' => 'videos_upload']) }}"
                                                data--submit='confirm_form' data--confirmation='true' data--ajax='true'
                                                data-toggle='tooltip'
                                                title='{{ __("message.remove_file_title" , ["name" =>  __("message.video") ]) }}'
                                                data-title='{{ __("message.remove_file_title" , ["name" =>  __("message.video") ]) }}'
                                                data-message='{{ __("message.remove_file_msg") }}'>
                                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.4"
                                                        d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                        fill="currentColor"></path>
                                                    <path
                                                        d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        <h6 class="ml-3">{{ (isset($data) ? $data->title : '') }}</h6>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right')->id('saveButton')  }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
    @section('bottom_script')
        <script>
            $(document).ready(function(){
                formValidation("#video_upload_validation_form", {
                    title: { required: true },
                    video_duration: { required: true },
                }, {
                    title: { required: "Please enter a Title." },
                    video_duration: { required: "Please enter a Video Duration."},
                });

                $('#videoUpload').on('change', function (event) {
                    var file = event.target.files[0];
                    if (file && file.size > 52428800) {
                        $('#error-message').show();
                        $(this).val('');
                        $('#saveButton').prop('disabled', true);
                    } else {
                        $('#error-message').hide();
                        $('#saveButton').prop('disabled', false);
                    }
                });
            });
        </script>
    @endsection
</x-master-layout>
