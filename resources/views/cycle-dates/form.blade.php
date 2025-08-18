<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null; ?>
        @if (isset($id))
            {{ html()->modelForm($data, 'PATCH', route('cycle-dates.update', $id))->attribute('enctype', 'multipart/form-data')->id('cycleday_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('cycle-dates.store'))->attribute('enctype', 'multipart/form-data')->id('cycleday_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('cycle-dates.index') }} " class="btn btn-sm btn-primary"
                                role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>')->class('form-control-label')->attribute('for', 'title') }}
                                    {{ html()->text('title')->class('form-control')->placeholder(__('message.title'))->attribute('autofocus', true) }}
                                </div>
                                <div class="form-group col-md-{{ isset($data) ? '4' : '6' }}">
                                    <label class="form-control-label" for="cycle_dates_thumbnail_image">{{ __('message.image') }}
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" name="cycle_dates_thumbnail_image" class="custom-file-input"
                                            accept="image/*">
                                        <label
                                            class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.image')]) }}</label>
                                    </div>
                                    <span class="selected_thumbnail_file"></span>
                                </div>
                                @if (isset($id) && getMediaFileExit($data, 'cycle_dates_thumbnail_image'))
                                    <div class="col-md-2 mb-2 mt-2">
                                        <img id="sub_symptom_icon_preview"
                                            src="{{ getSingleMedia($data, 'cycle_dates_thumbnail_image') }}" alt="cycle_dates_thumbnail_image"
                                            class="attachment-image mt-1">
                                        <a class="text-danger remove-file"
                                            href="{{ route('remove.file', ['id' => $data->id, 'type' => 'cycle_dates_thumbnail_image']) }}"
                                            data--submit='confirm_form' data--confirmation='true' data--ajax='true'
                                            data-toggle='tooltip'
                                            title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                            data-title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                            data-message='{{ __('message.remove_file_msg') }}'>
                                            <i class="ri-close-circle-line"></i>
                                        </a>
                                    </div>
                                @endif
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                                    {{ html()->select('goal_type', [0 => 'Track Cycle'], $params['goal_type'] ?? old('goal_type'))->class('select2js form-group type')->id('goal_type_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))->attribute('data-allow-clear', 'true') }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.day') . ' <span class="text-danger">*</span>')->for('day')->class('form-control-label') }}
                                    {{ html()->select('day', isset($id) ? [$data->day => $data->day . ' ' . __('message.day')] : [], old('day'))->class('select2js form-group')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.day')]))->attribute('data-ajax--url', route('ajax-list', ['type' => 'cycle_days']))->attribute('data-allow-clear', 'true') }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.view_type') . ' <span class="text-danger">*</span>')->for('view_type_id')->class('form-control-label') }}
                                    {{ html()->select(
                                            'view_type',
                                            ['' => ''] + getViewType() + [7 => __('message.text_message'), 8 => __('message.question_answer')],
                                            $data->view_type ?? old('view_type'),
                                        )->class('select2js form-group type')->id('view_type_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.view_type')]))->attribute('data-allow-clear', 'true')->required() }}
                                </div>
                                <div class="form-group col-md-6 d-none" id="for_cateogry">
                                    {{ html()->label(__('message.category'))->class('form-control-label') }}
                                    {{ html()->select(
                                            'category_id',
                                            isset($id) && optional($data->category)->id ? [$data->category->id => $data->category->name] : [],
                                            old('category_id'),
                                        )->class('select2js form-group category_id')->id('category_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.category')]))->attribute('data-allow-clear', 'true') }}
                                </div>
                                <div class="form-group col-md-6 d-none" id="for_story_image">
                                    <label class="form-control-label" for="image">{{ __('message.story_image') }}
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" style="line-height: 20px;"
                                            name="story_image[]" multiple>
                                        <label
                                            class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.story_image')]) }}</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 d-none" id="video_upload">
                                    {{ html()->label(__('message.video'))->class('form-control-label')->attribute('for', 'video_id') }}
                                    {{ html()->select(
                                            'video_id',
                                            isset($id) ? [optional($data->video)->id => optional($data->video)->title] : [],
                                            old('video_id'),
                                        )->class('select2js form-group')->id('video_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.video')]))->attribute('data-ajax--url', route('ajax-list', ['type' => 'video_data']))->attribute('data-allow-clear', 'true') }}
                                </div>
                                <div class="form-group col-md-{{ isset($id) && getMediaFileExit($data, 'image_video_image') ? 4 : 6 }} d-none view_type_image_video_image"
                                    id="">
                                    <label class="form-control-label" for="image">{{ __('message.image') }} </label>
                                    <div class="custom-file">
                                        <input type="file" name="image_video_image" class="custom-file-input"
                                            accept="image/*">
                                        <label
                                            class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.image')]) }}</label>
                                    </div>
                                </div>
                                @if (isset($id) && getMediaFileExit($data, 'image_video_image'))
                                    <div class="col-md-2 mb-2 d-none view_type_image_video_image">
                                        <img id="image_video_image_preview"
                                            src="{{ getSingleMedia($data, 'image_video_image') }}"
                                            alt="image_video_image-image" class="attachment-image mt-1">
                                        <a class="text-danger remove-file ml-1"
                                            href="{{ route('remove.file', ['id' => $data->id, 'type' => 'image_video_image']) }}"
                                            data--submit='confirm_form' data--confirmation='true' data--ajax='true'
                                            data-toggle='tooltip'
                                            title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                            data-title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                            data-message='{{ __('message.remove_file_msg') }}'>
                                            <i class="ri-close-circle-line"></i>
                                        </a>
                                    </div>
                                @endif
                                <div class="form-group col-md-{{ isset($id) && getMediaFileExit($data, 'video_image_video') ? 4 : 6 }} d-none view_type_video_image_video"
                                    id="view_type_video_image-video">
                                    <label class="form-control-label" for="image">{{ __('message.video') }} </label>
                                    <div class="custom-file">
                                        <input type="file" name="video_image_video" class="custom-file-input"
                                            accept="video/*">
                                        <label
                                            class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.video')]) }}</label>
                                    </div>
                                </div>
                                @if (isset($id) && getMediaFileExit($data, 'video_image_video'))
                                    <div class="col-md-2 mb-2 d-none view_type_video_image_video">
                                        <a href="{{ getSingleMedia($data, 'video_image_video') }}" class="popup-vimeo"
                                            id="video_image_video_preview"><video class="video_image_video_preview"
                                                src="{{ getSingleMedia($data, 'video_image_video') }}" id=""
                                                width="85%" height="100"></video></a>
                                        <a class="text-danger remove-file ml-1 mt-1"
                                            href="{{ route('remove.file', ['id' => $data->id, 'type' => 'video_image_video']) }}"
                                            data--submit='confirm_form' data--confirmation='true' data--ajax='true'
                                            data-toggle='tooltip'
                                            title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                            data-title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                            data-message='{{ __('message.remove_file_msg') }}'>
                                            <i class="ri-close-circle-line"></i>
                                        </a>
                                    </div>
                                @endif
                                <div class="form-group col-md-6 d-none" id="video_course_upload">
                                    {{ html()->label(__('message.video'), 'video_id_multiple')->class('form-control-label') }}
                                    {{ html()->select('video_id[]', $videosCourseList, old('video_id'))
                                        ->class('select2js form-group')
                                        ->id('video_id_multiple')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.video')]))
                                        ->attribute('data-allow-clear', 'true')
                                        ->multiple() 
                                    }}

                                </div>
                                
                                <div class="form-group col-md-6" id="podcast_upload">
                                    <label class="form-control-label" for="image">{{ __('message.podcast') }}
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" style="line-height: 20px;"
                                            name="section_data_podcast" accept="audio/*" multiple>
                                        <label
                                            class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.podcast')]) }}</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 d-none" id="blog_upload">
                                    {{ html()->label(__('message.article'))->class('form-control-label') }}
                                    {{ html()->select('article_id', $selected_article ?? [])
                                        ->class('select2js form-group')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article'])) 
                                        ->attribute('data-allow-clear', 'true')
                                         ->attribute('id', 'article_id') 
                                    }}
                                </div>
                                <div class="form-group col-md-6 d-none" id="blog_course_upload">
                                    {{ html()->label(__('message.blog_course') . ' <span class="text-danger">*</span>', 'blog_course_article_id')->class('form-control-label') }}
                                    {{ html()->select('blog_course_article_id[]', $selected_blog_course_article ?? [], $selected_blog_course_article ? $selected_blog_course_article->keys() : [])
                                        ->class('select2js form-group tags')
                                        ->multiple()
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.blog_course')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article'])) 
                                    }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>')->class('form-control-label')->attribute('for', 'status') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js')->id('status') }}
                                </div>
                                <div class="form-group col-md-12" id="for_story_image">
                                    @if (isset($id) && getMediaFileExit($data, 'story_image'))
                                        @php
                                            $images = $data->getMedia('story_image');
                                            $file_extensions = config('constant.IMAGE_EXTENSIONS', [
                                                'jpg',
                                                'jpeg',
                                                'png',
                                                'gif',
                                            ]);
                                        @endphp
                                        <div>
                                            @if (!empty($images) && count($images) > 0)
                                                <div class="row">
                                                    @foreach ($images as $image)
                                                        @php
                                                            $fileUrl = $image->getFullUrl();
                                                            $imageExtension = strtolower(
                                                                pathinfo($fileUrl, PATHINFO_EXTENSION),
                                                            );
                                                            $isImage = in_array($imageExtension, $file_extensions);
                                                        @endphp
                                                        <div class="col-md-1 pr-0 mb-3">
                                                            <a href="{{ $isImage ? $image->getUrl() : asset('images/file.png') }}"
                                                                class="magnific-popup-image-gallery avatar-100">
                                                                <img id="{{ $image->id }}_preview"
                                                                    src="{{ $isImage ? $image->getUrl() : asset('images/file.png') }}"
                                                                    alt="{{ $image->name }}"
                                                                    class="avatar-100 card-img-top">
                                                            </a>
                                                            <a class="text-danger remove-button position-absolute top-0 end-0"
                                                                href="{{ route('remove.file', ['id' => $image->id, 'type' => 'story_image']) }}"
                                                                data--submit='confirm_form' data--confirmation='true'
                                                                data--ajax='true' data-toggle='tooltip'
                                                                title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                                                data-title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                                                data-message='{{ __('message.remove_file_msg') }}'>
                                                                <i class="ri-close-circle-line fa-2x"></i>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group col-md-12" id="video_upload">
                                    @if (isset($id) && getMediaFileExit($data, 'insights_video'))
                                        <div class="col-md-2 mb-2 position-relative">
                                            <?php
                                            $file_extention = config('constant.VIDEO_EXTENSIONS');
                                            $video = getSingleMedia($data, 'insights_video');
                                            $extention = in_array(strtolower(videoExtension($video)), $file_extention);
                                            $path = parse_url($video, PHP_URL_PATH);
                                            $file_name = basename($path);
                                            ?>

                                            @if ($extention)
                                                <video width="350" height="280" controls>
                                                    <source src="{{ $video }}" type="video/mp4">
                                                </video>
                                            @else
                                                <video width="350" height="280" controls>
                                                    <source src="{{ $video }}" type="video/mp4">
                                                </video>
                                            @endif
                                            <a class="text-danger remove-file"
                                                href="{{ route('remove.file', ['id' => $data->id, 'type' => 'insights_video']) }}"
                                                data--submit='confirm_form' data--confirmation='true'
                                                data--ajax='true' data-toggle='tooltip'
                                                title='{{ __('message.remove_file_title', ['name' => __('message.video')]) }}'
                                                data-title='{{ __('message.remove_file_title', ['name' => __('message.video')]) }}'
                                                data-message='{{ __('message.remove_file_msg') }}'>
                                                <svg width="20" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.4"
                                                        d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                        fill="currentColor"></path>
                                                    <path
                                                        d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        <h6 class="ml-3">{{ isset($data) ? $data->title : '' }}</h6>
                                    @endif
                                </div>
                                <div class="form-group col-md-12" id="podcast_upload">
                                    @if (isset($id) && getMediaFileExit($data, 'section_data_podcast'))
                                        <h5>{{ __('message.podcast') }} :</h5>
                                        @php
                                            $podcast_audio = $data->getMedia('section_data_podcast');
                                            $file_extensions = config('constant.IMAGE_EXTENSIONS', []);
                                        @endphp
                                        <div class="row">
                                            @if ($podcast_audio && count($podcast_audio) > 0)
                                                @foreach ($podcast_audio as $audio)
                                                    <div class="row col-md-5 pr-0 text-center ml-2"
                                                        id="audio_preview_{{ $data->id }}">
                                                        <div class="card mt-2 p-2 border-0">
                                                            <div class="position-relative">
                                                                <div class="remove-button float-right">
                                                                    <a class="text-danger"
                                                                        href="{{ route('remove.file', ['id' => $audio->id, 'type' => 'section_data_podcast']) }}"
                                                                        data--submit='confirm_form'
                                                                        data--confirmation='true' data--ajax='true'
                                                                        data-toggle='tooltip'
                                                                        title='{{ __('message.remove_file_title', ['name' => __('message.podcast')]) }}'
                                                                        data-title='{{ __('message.remove_file_title', ['name' => __('message.podcast')]) }}'
                                                                        data-message='{{ __('message.remove_file_msg') }}'>
                                                                        <i class="ri-close-circle-line fa-2x"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="audio-content">
                                                                    <a href="{{ $audio->getUrl() }}"
                                                                        class="imaget_file-image-gallery"
                                                                        title="{{ $audio->name }}">
                                                                        @if (in_array($audio->mime_type, ['audio/mp3', 'audio/mpeg', 'audio/ogg']))
                                                                            <audio controls>
                                                                                <source src="{{ $audio->getUrl() }}">
                                                                            </audio>
                                                                        @else
                                                                            <i
                                                                                class="fas fa-file-audio fa-3x mt-5 ml-2 text-dark"></i>
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Text Message Sec --}}
                            <div id="text-message-sec" style="display: block;">
                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button" id="add-text-message-row" class="btn btn-outline-danger d-flex align-items-center">
                                        <i class="fa fa-plus-circle mr-2" style="font-size: 22px; color:#ff5783;"></i>
                                        {{__('message.add_form_title',['form' => __('message.section')]) }}
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0" id="text-message-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{ __('message.title') }}</th>
                                                    <th>{{ __('message.answer') }}</th>
                                                    <th>{{ __('message.image') }}</th>
                                                    <th>*</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($data) && $data->textmessage_data->count())
                                                    @foreach($data->textmessage_data as $index => $textmessage)
                                                        <input type="hidden" name="cycle_date_data_ids[]" value="{{ isset($id) ? $textmessage->id : '' }}">
                                                        <tr>
                                                            <td>
                                                                {{ html()->text('title_answer[]', $textmessage->title)->class('form-control')->placeholder(__('message.title')) }}
                                                            </td>

                                                            <td>
                                                                {{ html()->textarea('answer[]', $textmessage->message)->class('form-control')->placeholder('Answer')->rows(2)->cols(40) }}
                                                            </td>

                                                            <td>
                                                                <div class="custom-file">
                                                                    <input type="file" name="cycle_date_data_text_message_image[]" class="custom-file-input" accept="image/*">
                                                                    <label class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.image')]) }}</label>
                                                                </div>

                                                                @if(getMediaFileExit($textmessage, 'cycle_date_data_text_message_image'))
                                                                    <div class="d-flex align-items-center mt-2">
                                                                        <img src="{{ getSingleMedia($textmessage, 'cycle_date_data_text_message_image') }}"
                                                                            alt="header-image"
                                                                            class="attachment-image"
                                                                            style="max-width: 100px;">

                                                                        <a class="text-danger remove-file ml-2"
                                                                        href="{{ route('remove.file', ['id' => $textmessage->id, 'type' => 'cycle_date_data_text_message_image']) }}"
                                                                        data--submit='confirm_form'
                                                                        data--confirmation='true'
                                                                        data--ajax='true'
                                                                        data-toggle='tooltip'
                                                                        title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                                                        data-title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                                                        data-message='{{ __('message.remove_file_msg') }}'>
                                                                            <i class="ri-close-circle-line" style="margin-top:12px;"></i>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-danger btn-sm remove-row {{ $index == 0 ? 'disabled' : '' }}" {{ $index == 0 ? 'disabled' : '' }}>-</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            {{ html()->text('title_answer[]')->class('form-control')->placeholder(__('message.title'))->attribute('autofocus', true) }}
                                                        </td>

                                                        <td>
                                                            {{ html()->textarea('answer[]')->class('form-control')->placeholder('Answer')->rows(2)->cols(40) }}
                                                        </td>

                                                        <td>
                                                            <div class="custom-file">
                                                                <input type="file" name="cycle_date_data_text_message_image[]" class="custom-file-input" accept="image/*">
                                                                <label class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.image')]) }}</label>
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-danger btn-sm remove-row" disabled>-</button>
                                                        </td>
                                                    </tr>
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                           {{-- Question/Answer Sec --}}
                            <div id="question-answer-sec" style="display:block;">
                                <div class="d-flex justify-content-end mb-3 mt-3">
                                    <button type="button" id="add-qa-row" class="btn btn-outline-danger d-flex align-items-center">
                                        <i class="fa fa-plus-circle mr-2" style="font-size: 22px; color:#ff5783;"></i>
                                       {{__('message.add_form_title',['form' => __('message.section')]) }}
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0" id="qa-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 5%;">#</th>
                                                    <th style="width: 20%;">{{ __('message.image') }}</th>
                                                    <th>{{ __('message.title') }}</th>
                                                    <th>{{ __('message.question') }}</th>
                                                    <th>{{ __('message.answer') }}</th>
                                                    <th style="width: 5%;">*</th>
                                                </tr>
                                            </thead>
                                            <tbody id="qa-tbody">
                                                @if(isset($data) && $data->textmessage_data->count())
                                                    @php $group = 1; @endphp
                                                    @foreach($data->textmessage_data as $index => $textmessage)
                                                        @if($loop->iteration % 2 == 1)
                                                            <tr>
                                                                <td rowspan="2" class="align-middle text-center">{{ $group }}</td>
                                                                <input type="hidden" name="cycle_date_data_id[{{ $group }}][]" value="{{ $textmessage->id }}">
                                                                <td>
                                                                    <div class="custom-file">
                                                                        <input type="file" name="cycle_date_data_que_ans_image_[{{ $group }}][]" class="custom-file-input" accept="image/*">
                                                                        <label class="custom-file-label">
                                                                            {{ $textmessage->image ? 'Uploaded' : __('message.choose_file', ['file' => __('message.image')]) }}
                                                                        </label>
                                                                        @if(getMediaFileExit($textmessage, 'cycle_date_data_que_ans_image[{{ $group }}]'))
                                                                            <div class="d-flex align-items-center mt-2">
                                                                                <img src="{{ getSingleMedia($textmessage, 'cycle_date_data_text_message_image') }}"
                                                                                    alt="header-image"
                                                                                    class="attachment-image"
                                                                                    style="max-width: 100px;">

                                                                                <a class="text-danger remove-file ml-2"
                                                                                href="{{ route('remove.file', ['id' => $textmessage->id, 'type' => 'cycle_date_data_text_message_image']) }}"
                                                                                data--submit='confirm_form'
                                                                                data--confirmation='true'
                                                                                data--ajax='true'
                                                                                data-toggle='tooltip'
                                                                                title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                                                                data-title='{{ __('message.remove_file_title', ['name' => __('message.image')]) }}'
                                                                                data-message='{{ __('message.remove_file_msg') }}'>
                                                                                    <i class="ri-close-circle-line" style="margin-top:12px;"></i>
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td><input type="text" name="title_name[{{ $group }}][]" class="form-control" value="{{ $textmessage->title }}" placeholder="{{ __('message.title') }}"></td>
                                                                <td><input type="text" name="qa_question[{{ $group }}][]" class="form-control" value="{{ $textmessage->question }}" placeholder="{{ __('message.question') }}"></td>
                                                                <td><input type="text" name="qa_answer[{{ $group }}][]" class="form-control" value="{{ $textmessage->answer }}" placeholder="{{ __('message.answer') }}"></td>
                                                                <td rowspan="2" class="text-center align-middle">
                                                                    <button type="button" class="btn btn-danger btn-sm remove-qa-pair">-</button>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <div class="custom-file">
                                                                        <input type="file" name="cycle_date_data_que_ans_image_[{{ $group }}][]" class="custom-file-input" accept="image/*">
                                                                        <label class="custom-file-label">
                                                                            {{ $textmessage->image ? 'Uploaded' : __('message.choose_file', ['file' => __('message.image')]) }}
                                                                        </label>
                                                                        @if($textmessage->image)
                                                                            <a href="{{ asset('uploads/your-folder/'.$textmessage->image) }}" target="_blank">View</a>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td><input type="text" name="title_name[{{ $group }}][]" class="form-control" value="{{ $textmessage->title }}" placeholder="{{ __('message.title') }}"></td>
                                                                <td><input type="text" name="qa_question[{{ $group }}][]" class="form-control" value="{{ $textmessage->question }}" placeholder="{{ __('message.question') }}"></td>
                                                                <td><input type="text" name="qa_answer[{{ $group }}][]" class="form-control" value="{{ $textmessage->answer }}" placeholder="{{ __('message.answer') }}"></td>
                                                            </tr>
                                                            @php $group++; @endphp
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @php $group = 1; @endphp
                                                    @for ($i = 0; $i < 1; $i++)
                                                        <tr>
                                                            <td rowspan="2" class="align-middle text-center">{{ $group }}</td>
                                                            <td>
                                                                <div class="custom-file">
                                                                    <input type="file" name="cycle_date_data_que_ans_image_[{{ $i }}][]" class="custom-file-input" accept="image/*">
                                                                    <label class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.image')]) }}</label>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="title_name[{{ $i }}][]" class="form-control" placeholder="{{ __('message.title') }}"></td>
                                                            <td><input type="text" name="qa_question[{{ $i }}][]" class="form-control" placeholder="{{ __('message.question') }}"></td>
                                                            <td><input type="text" name="qa_answer[{{ $i }}][]" class="form-control" placeholder="{{ __('message.answer') }}"></td>
                                                            <td rowspan="2" class="text-center align-middle">
                                                                <button type="button" class="btn btn-danger btn-sm remove-qa-pair">-</button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="custom-file">
                                                                    <input type="file" name="cycle_date_data_que_ans_image_[{{ $i }}][]" class="custom-file-input" accept="image/*">
                                                                    <label class="custom-file-label">{{ __('message.choose_file', ['file' => __('message.image')]) }}</label>
                                                                </div>
                                                            </td>
                                                            <td><input type="text" name="title_name[{{ $i }}][]" class="form-control" placeholder="{{ __('message.title') }}"></td>
                                                            <td><input type="text" name="qa_question[{{ $i }}][]" class="form-control" placeholder="{{ __('message.question') }}"></td>
                                                            <td><input type="text" name="qa_answer[{{ $i }}][]" class="form-control" placeholder="{{ __('message.answer') }}"></td>
                                                        </tr>
                                                        @php $group++; @endphp
                                                    @endfor
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>

    @section('bottom_script')
        {{-- Text Message --}}
        <script>
            $(document).ready(function() {

                function updateRemoveButtonState() {
                    let rowCount = $('#text-message-table tbody tr').length;
                    if (rowCount > 1) {
                        $('#text-message-table tbody tr:first').find('.remove-row').prop('disabled', false);
                    } else {
                        $('#text-message-table tbody tr:first').find('.remove-row').prop('disabled', true);
                    }
                }

                // $('#article_id').val(null).trigger('change');
                $('#add-text-message-row').click(function() {
                    let newRow = `<tr>
                                    <td><input type="text" name="title_answer[]" class="form-control" placeholder="Title"></td>
                                    <td><textarea name="answer[]" class="form-control" placeholder="Answer" rows="2" cols="40"></textarea></td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="file" name="cycle_date_data_text_message_image[]" class="custom-file-input" accept="image/*">
                                            <label class="custom-file-label">Choose Image</label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row">-</button>
                                    </td>
                                </tr>`;

                    $('#text-message-table tbody').append(newRow);
                    updateRemoveButtonState();
                });

                // Remove row functionality for dynamic rows
                $(document).on('click', '.remove-row', function() {
                    // Prevent deleting first row directly unless it's enabled
                    if ($(this).closest('tr').is(':first-child') && $(this).is(':disabled')) {
                        return;
                    }

                    $(this).closest('tr').remove();
                    updateRemoveButtonState();
                });

                updateRemoveButtonState();
            });
        </script>
        {{-- Question Answer --}}
        <script>
            $(document).ready(function () {
                function updateQaRowState() {
                    let groups = $('#qa-table tbody tr').length / 2;

                    if (groups > 1) {
                        $('.remove-qa-pair').prop('disabled', false);
                    } else {
                        $('.remove-qa-pair').prop('disabled', true);
                    }

                    // Update group serial numbers
                    let groupCounter = 1;
                    $('#qa-table tbody tr').each(function (index) {
                        if (index % 2 === 0) { // Only update first row of each pair
                            $(this).find('td:first').text(groupCounter);
                            groupCounter++;
                        }
                    });
                }

                $('#add-qa-row').click(function () {
                    let groupCount = $('#qa-table tbody tr').length / 2;
                    let newGroupNumber = groupCount + 1;

                    let newGroup = `
                        <tr>
                            <td rowspan="2" class="align-middle text-center">${newGroupNumber}</td>
                            <td>
                                <div class="custom-file">
                                    <input type="file" name="cycle_date_data_que_ans_image[${groupCount}][]" class="custom-file-input" accept="image/*">
                                    <label class="custom-file-label">Choose Image</label>
                                </div>
                            </td>
                            <td><input type="text" name="title_name[${groupCount}][]" class="form-control" placeholder="Title"></td>
                            <td><input type="text" name="qa_question[${groupCount}][]" class="form-control" placeholder="Question"></td>
                            <td><input type="text" name="qa_answer[${groupCount}][]" class="form-control" placeholder="Answer"></td>
                            <td rowspan="2" class="text-center align-middle">
                                <button type="button" class="btn btn-danger btn-sm remove-qa-pair">-</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="custom-file">
                                    <input type="file" name="cycle_date_data_que_ans_image[${groupCount}][]" class="custom-file-input" accept="image/*">
                                    <label class="custom-file-label">Choose Image</label>
                                </div>
                            </td>
                            <td><input type="text" name="title_name[${groupCount}][]" class="form-control" placeholder="Title"></td>
                            <td><input type="text" name="qa_question[${groupCount}][]" class="form-control" placeholder="Question"></td>
                            <td><input type="text" name="qa_answer[${groupCount}][]" class="form-control" placeholder="Answer"></td>
                        </tr>
                    `;personbody').append(newGroup);

                    updateQaRowState();
                });

                $(document).on('click', '.remove-qa-pair', function () {
                    let row = $(this).closest('tr');
                    row.next('tr').remove();
                    row.remove();

                    updateQaRowState();
                });

                // Initial call
                updateQaRowState();
            });

        </script>

        <script>
            $(document).ready(function() {
                // Form Validation
                const isImageUploaded =
                    {{ isset($id) && getMediaFileExit($data, 'thumbnail_image') ? 'true' : 'false' }};
                formValidation("#cycleday_validation_form", {
                    title: {
                        required: true
                    },
                    goal_type: {
                        required: true
                    },
                    view_type: {
                        required: true
                    },
                    day: {
                        required: true
                    },
                    video_id: {
                        required: true
                    },
                }, {
                    title: {
                        required: "Please enter a Title."
                    },
                    goal_type: {
                        required: "Please select a goal type."
                    },
                    view_type: {
                        required: "Please select a View type."
                    },
                    day: {
                        required: "Please select a  Day."
                    },
                    video_id: {
                        required: "Please select a  Video."
                    },
                });

                // Initialize Select2
                $('.select2js').select2();
              
             function setAllValidations() {
                // Remove existing validation rules
                $('[name^="title_name"], [name^="qa_question"], [name^="qa_answer"], [name="title_answer[]"], [name="answer[]"]').each(function() {
                    $(this).rules("remove");
                });

                // QA Section validations
                $('[name^="title_name"]').each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please enter a title."
                        }
                    });
                });

                $('[name^="qa_question"]').each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please enter a question."
                        }
                    });
                });

                $('[name^="qa_answer"]').each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please enter an answer."
                        }
                    });
                });

                // Text Message Section validations
                $('[name="title_answer[]"]').each(function() {
                    console.log([name="title_answer[]"]);
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please enter a title."
                        }
                    });
                });

                $('[name="answer[]"]').each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please enter an answer."
                        }
                    });
                });
            }

            setAllValidations();

            // Re-apply validation when any row is added dynamically
            $(document).on('click', '#add-qa-row, #add-text-message-row', function() {
                setTimeout(function() {
                    setAllValidations();
                }, 200);
            });

                // Initialize category data on page load
                const initialGoalType = "{{ old('goal_type') ?? (isset($data) ? $data->goal_type : '') }}";
                categoryData($('#category_id').val(), initialGoalType);

                // Handle goal type change
                $(document).on('change', '#goal_type_id', function() {
                    categoryData($(this).val());
                });

                function categoryData(goalTypeId, selected = '') {
                    const catUrl =
                        "{{ route('ajax-list', ['type' => 'get_category_by_goal_type', 'goal_type' => '']) }}" +
                        goalTypeId;
                    $.ajax({
                        url: catUrl.replace('amp;', ''),
                        success: function(result) {
                            const categoryID = $('#category_id');
                            categoryID.empty();
                            if (result.results) {
                                categoryID.select2({
                                    width: '100%',
                                    placeholder: "{{ __('message.select_name', ['select' => __('message.symptoms')]) }}",
                                    data: result.results
                                });
                                if (selected) {
                                    categoryID.val(selected).trigger('change');
                                }
                            }
                        }
                    });
                }

                // Handle view type change
               function handleViewType(viewId) {
                // Hide all relevant sections first
                $("#for_story_image, #story_view_article, #video_upload, #for_cateogry, #video_course_upload, #blog_course_upload, #podcast_upload, #blog_upload, #video_blog_description, #text-message-sec, #question-answer-sec").addClass('d-none');
                $('.view_type_image_video_image, .view_type_video_image_video').addClass('d-none');

                // Show based on viewId
                switch (viewId) {
                    case '0':
                        $("#for_story_image, #story_view_article").removeClass('d-none');
                        break;
                    case '1':
                        $("#video_upload").removeClass('d-none');
                        break;
                    case '2':
                        $("#for_cateogry").removeClass('d-none');
                        $('#goal_type_id').trigger('change');
                        break;
                    case '3':
                        $("#video_course_upload, #video_blog_description").removeClass('d-none');

                        break;
                    case '4':
                        $("#blog_course_upload, #video_blog_description").removeClass('d-none');
                        break;
                    case '5':
                        $("#podcast_upload").removeClass('d-none');
                        break;
                    case '6':
                        $("#blog_upload").removeClass('d-none');
                        break;
                    case '7':
                        $("#text-message-sec, #blog_upload").removeClass('d-none');
                        break;
                    case '8':
                        $("#question-answer-sec, #blog_upload").removeClass('d-none');
                        break;
                    default:
                        break;
                }

                // Clear fields when not 5,7,8
                if (!['5', '7', '8'].includes(viewId)) {
                    clearFields();
                }
            }
            function clearFields() {
                $('#text-message-sec input').val('');
                $('#text-message-sec select').val('').trigger('change');
            }

            // On page load — set initial value if available
            const viewTypeData = "{{ old('view_type_id') ?? (isset($data) ? $data->view_type : '') }}";
            handleViewType(viewTypeData);
            $('#view_type_id').val(viewTypeData).trigger('change');

            // On change event
            $(document).on('change', '#view_type_id', function () {
                handleViewType($(this).val());
            });

                // Dynamic Row Addition
                $('#add_button').on('click', function() {
                    const tableBody = $('#insghts tbody');
                    const lastRow = tableBody.find('tr:last');
                    lastRow.find('select.select2js').select2('destroy');
                    const newRow = lastRow.clone();
                    const newRowId = parseInt(lastRow.attr('row')) + 1;

                    newRow.attr({
                        id: 'row_' + newRowId,
                        row: newRowId,
                        'data-id': 0
                    });

                    // Reset input fields
                    newRow.find('input, textarea').each(function() {
                        const input = $(this);
                        const nameAttr = input.attr('name');
                        const updatedName = nameAttr.replace(/\[\d+\]/, '[' + newRowId + ']');
                        input.attr('name', updatedName);
                        input.is(':file') ? input.replaceWith(
                            `<input type="file" name="${updatedName}" class="custom-file-input" accept="image/*">`
                        ) : input.val('');
                    });

                    newRow.find('.custom-file-label').text('Choose Image');

                    // Reset selects
                    newRow.find('select.select2js').each(function() {
                        const select = $(this);
                        const updatedName = select.attr('name').replace(/\[\d+\]/, '[' + newRowId +
                            ']');
                        select.attr('name', updatedName).empty();

                        if (updatedName.includes('message_status')) {
                            select.append(
                                '<option value="1" selected>Active</option><option value="0">Inactive</option>'
                            );
                        } else if (updatedName.includes('message_article_id')) {
                            select.append('<option></option>');
                            select.select2({
                                placeholder: 'Select Article',
                                allowClear: true,
                                ajax: {
                                    url: select.data('ajax--url'),
                                    dataType: 'json'
                                }
                            });
                        } else {
                            select.select2({
                                placeholder: 'Select',
                                allowClear: true
                            });
                        }
                    });

                    lastRow.after(newRow);
                    updateSerialNumbers();
                });

                $('#insghts').on('click', '.removebtn', function() {
                    const rowCount = $('#table_list tr').length;
                    if (rowCount > 1) {
                        $(this).closest('tr').remove();
                        updateSerialNumbers();
                    } else {
                        alert('You cannot delete the last row.');
                    }
                });

                function updateSerialNumbers() {
                    $('#table_list tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                }
            });
        </script>
    @endsection

</x-master-layout>
