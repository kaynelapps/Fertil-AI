<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('section-data.update', $id))->attribute('enctype', 'multipart/form-data')->id('section_data_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('section-data.store'))->attribute('enctype','multipart/form-data')->id('section_data_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ url()->previous() }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                
                                <div class="form-group col-md-6">
                                    {!! html()->hidden('main_title_id', request()->id) !!}
                                    {!! html()->hidden('request_category_id', request()->category_id) !!}

                                    {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>', 'title')->class('form-control-label') }}
                                    {{ html()->text('title')->placeholder(__('message.title'))->class('form-control')->autofocus() }}
                                </div>
                                
                                <div class="form-group col-md-{{ isset($data) ? '4' : '6' }}">
                                    <label class="form-control-label" for="section_data_image">{{ __('message.section_data_image') }} </label><span class="text-danger"> *</span>
                                    <div class="custom-file">
                                        <input type="file" name="section_data_image" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                    </div>
                                    <span class="selected_thumbnail_file"></span>
                                </div>
                                @if( isset($id) && getMediaFileExit($data, 'section_data_image'))
                                    <div class="col-md-2 mb-2 mt-2">
                                        <img id="sub_symptom_icon_preview" src="{{ getSingleMedia($data,'section_data_image') }}" alt="category-image" class="attachment-image mt-1">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'section_data_image']) }}"
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
                                    {{ html()->label(__('message.view_type').' <span class="text-danger">*</span>', 'view_type')->class('form-control-label') }}
                                    {{ html()->select('view_type', ['' => ''] + getViewType(), $data->view_type ?? old('view_type'))
                                        ->class('select2js form-group type')
                                        ->id('view_type_id')
                                        ->attribute('data-placeholder', __('message.select_name', [ 'select' => __('message.view_type') ]))
                                        ->attribute('data-allow-clear', 'true')->required()
                                    }}
                                </div>

                                <div class="form-group col-md-6 d-none" id="for_cateogry">
                                    {{ html()->label(__('message.category'), 'category_id')->class('form-control-label') }}
                                    {{ html()->select('category_id', isset($id) ? [optional($data->category)->id => optional($data->category)->name] : ['' => ''], old('category_id'))
                                        ->class('select2js form-group')
                                        ->id('category_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.category')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_category_section_main_section_data', 'secation_data_main_category_id' => $main_section_category_id]))
                                        ->attribute('data-allow-clear', 'true')
                                    }}
                                </div>

                                <div class="form-group col-md-6 d-none" id="for_story_image">
                                    <label class="form-control-label" for="image">{{ __('message.story_image') }} </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" style="line-height: 20px;" name="section_data_story_image[]"  multiple>
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.story_image') ]) }}</label>
                                    </div>
                                </div>

                                <div class="form-group col-md-6 d-none" id="video_upload">
                                    {{ html()->label(__('message.video'), 'video_id_single')->class('form-control-label') }}
                                    {{ html()->select('video_id', ['' => ''] + $videosList, old('video_id'))
                                        ->class('select2js form-group')
                                        ->id('video_id_single')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.video')]))
                                        ->attribute('data-allow-clear', 'true') 
                                    }}
                                </div>
                                
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
                                
                                <div class="form-group col-md-6 d-none" id="blog_course_upload">
                                    {{ html()->label(__('message.blog_course') . ' <span class="text-danger">*</span>', 'blog_course_article_id')->class('form-control-label') }}
                                    {{ html()->select('blog_course_article_id[]', $selected_blog_course_article ?? [], old('blog_course_article_id'))
                                        ->class('select2js form-group tags')
                                        ->multiple()
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.blog_course')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article'])) 
                                    }}
                                </div>
                                
                                <div class="form-group col-md-6 d-none" id="blog_upload">
                                    {{ html()->label(__('message.article') . ' <span class="text-danger">*</span>', 'blog_article_id')->class('form-control-label') }}
                                    {{ html()->select('blog_article_id', $selected_blog_article ?? [], old('blog_article_id'))
                                        ->class('select2js form-group')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article'])) 
                                    }}
                                </div>
                                
                                <div class="form-group col-md-6 d-none" id="podcast_upload">
                                    <label class="form-control-label" for="image">{{ __('message.podcast') }} </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" style="line-height: 20px;" name="section_data_podcast"  accept="audio/*" multiple>
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.podcast') ]) }}</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 d-none" id="story_view_article">
                                    {{ html()->label(__('message.article') . ' <span class="text-danger">*</span>', 'article_id')->class('form-control-label') }}
                                    {{ html()->select('article_id', $selected_article ?? [])
                                        ->class('select2js form-group')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article'])) 
                                    }}
                                </div>
                               
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js')->required() }}
                                </div>

                                <div class="form-group col-md-6">
                                </div>
                                
                                <div class="form-group col-md-6 d-none" id="video_blog_description">
                                    {{ html()->label(__('message.description'), 'description')->class('form-control-label') }}
                                    {{ html()->textarea('description')->class('form-control tinymce-description')->placeholder(__('message.description'))->rows(3)->cols(40) }}
                                </div>

                                <div class="form-group col-md-12" id="for_story_image">
                                    @if(isset($id) && getMediaFileExit($data, 'section_data_story_image'))
                                        <h5>{{ __('message.story_image') }} :</h5>
                                        @php
                                            $images = $data->getMedia('section_data_story_image');
                                            $file_extensions = config('constant.IMAGE_EXTENSIONS', []);
                                        @endphp
                                        <div class="row">
                                            @if (!empty($images))
                                                @foreach($images as $image)
                                                    @php
                                                        $extension = in_array(strtolower(imageExtention($image->getFullUrl())), $file_extensions);
                                                    @endphp
                                                    <div class="row col-md-1 pr-0 text-center ml-2" id="image_preview_{{$data->id}}">
                                                        <div class="card mt-2 p-2">
                                                            <div class="position-relative">
                                                                <a class="text-danger remove-button position-absolute top-0 end-0 mt-2 mr-0 ml-3" href="{{ route('remove.file', ['id' => $image->id, 'type' =>'story_image']) }}"
                                                                    data--submit='confirm_form'
                                                                    data--confirmation='true'
                                                                    data--ajax='true'
                                                                    data-toggle='tooltip'
                                                                    title='{{ __("message.remove_file_title" , ["name" => __("message.image") ]) }}'
                                                                    data-title='{{ __("message.remove_file_title" , ["name" => __("message.image") ]) }}'
                                                                    data-message='{{ __("message.remove_file_msg") }}'>
                                                                    <i class="ri-close-circle-line fa-2x"></i>
                                                                </a>
                                                                <div class="">
                                                                    <a href="{{ $image->getUrl() }}" class="imaget_file-image-gallery" title="{{ $image->name }}">
                                                                        @if(in_array($image->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                                                            <img id="{{ $image->id }}_preview" src="{{ $image->getUrl() }}" alt="{{ $image->name }}" class="avatar-100 card-img-top">
                                                                        @else
                                                                            <img src="{{ asset('images/file.png') }}" class="row avatar-100 m-1 card-img-top">
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
                                
                                <div class="form-group col-md-12" id="video_upload">
                                    @if( isset($id) && getMediaFileExit($data, 'section_data_video'))
                                        <div class="col-md-2 mb-2 position-relative">
                                            <?php
                                                $file_extention = config('constant.VIDEO_EXTENSIONS');
                                                $video = getSingleMedia($data, 'section_data_video');
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
                                                href="{{ route('remove.file', ['id' => $data->id, 'type' => 'section_data_video']) }}"
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
                                <div class="form-group col-md-12" id="podcast_upload">
                                    @if(isset($id) && getMediaFileExit($data, 'section_data_podcast'))
                                        <h5>{{ __('message.podcast') }} :</h5>
                                        @php
                                            $podcast_audio = $data->getMedia('section_data_podcast');
                                            $file_extensions = config('constant.IMAGE_EXTENSIONS', []);
                                        @endphp
                                        <div class="row">
                                            @if ($podcast_audio && count($podcast_audio) > 0)
                                                @foreach($podcast_audio as $audio)
                                                    <div class="row col-md-5 pr-0 text-center ml-2" id="audio_preview_{{$data->id}}">
                                                        <div class="card mt-2 p-2 border-0">
                                                            <div class="position-relative">
                                                                <div class="remove-button float-right">
                                                                    <a class="text-danger" href="{{ route('remove.file', ['id' => $audio->id, 'type' =>'section_data_podcast']) }}"
                                                                        data--submit='confirm_form'
                                                                        data--confirmation='true'
                                                                        data--ajax='true'
                                                                        data-toggle='tooltip'
                                                                        title='{{ __("message.remove_file_title" , ["name" => __("message.podcast") ]) }}'
                                                                        data-title='{{ __("message.remove_file_title" , ["name" => __("message.podcast") ]) }}'
                                                                        data-message='{{ __("message.remove_file_msg") }}'>
                                                                        <i class="ri-close-circle-line fa-2x"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="audio-content">
                                                                    <a href="{{ $audio->getUrl() }}" class="imaget_file-image-gallery" title="{{ $audio->name }}">
                                                                        @if(in_array($audio->mime_type, ['audio/mp3', 'audio/mpeg','audio/ogg']))
                                                                            <audio controls>
                                                                                <source src="{{ $audio->getUrl() }}">
                                                                            </audio>
                                                                        @else
                                                                            <i class="fas fa-file-audio fa-3x mt-5 ml-2 text-dark"></i>
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
    <script>
        $(document).ready(function(){
            var isImageUploaded = {{ isset($id) && getMediaFileExit($data, 'section_data_image') ? 'true' : 'false' }};
            formValidation("#section_data_validation_form", {
                title: { required: true },
                view_type: { required: true },
                article_id: { required: true },
                section_data_image: { required: !isImageUploaded },
            }, {
                title: { required: "Please enter a Title." },
                view_type: { required: "Please select a View type."},
                article_id: { required: "Please select a Article."},
                section_data_image: { required: "Please select a Section Data Image."}
            });
        });
    </script>
    <script>
        (function ($) {
            $(document).ready(function () {                
                $(document).on('change', '#view_type_id', function () {
                    var view_id = $(this).val();
                
                    $("#for_story_image, #story_view_article, #video_upload, #for_cateogry, #video_course_upload, #blog_course_upload, #podcast_upload, #blog_upload, #video_blog_description").addClass('d-none');
                
                    switch(view_id) {
                        case '0':
                            $("#for_story_image, #story_view_article").removeClass('d-none');
                            break;
                        case '1':
                            $("#video_upload").removeClass('d-none');
                            break;
                        case '2':
                            $("#for_cateogry").removeClass('d-none');
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
                        default:
                            break;
                    }
                });                

                var view_type_data = "{{ old('view_type_id') ?? (isset($data) ? $data->view_type : '') }}";
                var category_id = "{{ old('category_id') ?? (isset($data) ? $data->category_id : '') }}";

                if (view_type_data == 0 && view_type_data != '') {
                    $("#for_story_image").removeClass('d-none');
                    $("#story_view_article").removeClass('d-none');
                }
                
                if (view_type_data == 1 && view_type_data != '') {
                    $("#video_upload").removeClass('d-none');
                }

                if (view_type_data == 2 && view_type_data != '') {
                    $("#goal_type_id").trigger('change')
                    $("#for_cateogry").removeClass('d-none');
                }

                if (view_type_data == 3 && view_type_data != '') {
                    $("#video_course_upload").removeClass('d-none');
                }

                if (view_type_data == 4 && view_type_data != '') {
                    $("#blog_course_upload").removeClass('d-none');
                }

                if (view_type_data == 5 && view_type_data != '') {
                    $("#podcast_upload").removeClass('d-none');
                }

                if (view_type_data == 6 && view_type_data != '') {
                    $("#blog_upload").removeClass('d-none');
                }

                if ((view_type_data == 3 || view_type_data == 4) && view_type_data != '') {
                    $("#video_blog_description").removeClass('d-none');
                }
            });
        })(jQuery);
    </script>
@endsection

</x-master-layout>
