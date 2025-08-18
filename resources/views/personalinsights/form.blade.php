
<x-master-layout :assets="$assets ?? []">
   <div>
       <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('personalinsights.update', $id))->attribute('enctype', 'multipart/form-data')->id('personalinsights_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('personalinsights.store'))->attribute('enctype','multipart/form-data')->id('personalinsights_validation_form')->open() }}
        @endif
       <div class="row">
           <div class="col-lg-12">
               <div class="card">
                   <div class="card-header d-flex justify-content-between">
                       <div class="header-title">
                           <h4 class="card-title">{{ $pageTitle }}</h4>
                       </div>
                       <div class="card-action">
                           <a href="{{ route('personalinsights.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                       </div>
                   </div>

                   <div class="card-body">
                       <div class="new-user-info">
                           <div class="row">
                               <div class="form-group col-md-6">
                                    {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>')->class('form-control-label')->attribute('for', 'title')  }}
                                    {{ html()->text('title', old('title'))->placeholder(__('message.title'))->class('form-control')->autofocus() }}
                               </div>
                               
                               <div class="form-group col-md-{{ isset($data) ? '4' : '6' }}">
                                   <label class="form-control-label" for="thumbnail_image">{{ __('message.thumbnail_image') }} </label><span class="text-danger"> *</span>
                                   <div class="custom-file">
                                       <input type="file" name="thumbnail_image" class="custom-file-input" accept="image/*">
                                       <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                   </div>
                                   <span class="selected_thumbnail_file"></span>
                               </div>
                               @if( isset($id) && getMediaFileExit($data, 'thumbnail_image'))
                                   <div class="col-md-2 mb-2 mt-2">
                                       <img id="sub_symptom_icon_preview" src="{{ getSingleMedia($data,'thumbnail_image') }}" alt="category-image" class="attachment-image mt-1">
                                       <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'thumbnail_image']) }}"
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
                                    {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>')->class('form-control-label')->attribute('for', 'goal_type_id') }}
                                    {{ html()->select('goal_type', ['' => ''] + getGoalType(), $data->type ?? old('goal_type'))
                                        ->class('select2js form-group type')
                                        ->id('goal_type_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))
                                        ->attribute('data-allow-clear', 'true')
                                    }}
                               </div>
                               <div class="form-group col-md-6">
                                    {{ html()->label(__('message.insights_use_for') . ' <span class="text-danger">*</span>')->class('form-control-label')->attribute('for', 'insights_type') }}
                                    {{ html()->select('insights_type', ['' => ''] + getArticleType(), $data->insights_type ?? old('insights_type'))
                                        ->class('select2js form-group type')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.insights_use_for')]))
                                        ->attribute('data-allow-clear', 'true')
                                    }}
                               </div>

                                <div class="form-group col-md-6">
                                  {{ html()->label(__('message.view_type') . ' <span class="text-danger">*</span>')->for('view_type_id')->class('form-control-label') }}
                                  {{ html()->select('view_type', ['' => ''] + getViewType() + [7 => __('message.text')], $data->type ?? old('type'))
                                        ->class('select2js form-group type')
                                        ->id('view_type_id')
                                        ->attribute('data-placeholder', __('message.select_name', [ 'select' => __('message.view_type') ]))
                                        ->attribute('data-allow-clear', 'true')
                                        ->required()
                                   }}
                                </div>
                                <div class="form-group col-md-6 d-none" id="for_cateogry">
                                    {{ html()->label(__('message.category'))->class('form-control-label')->attribute('for', 'category_id') }}
                                    {{ html()->select('category_id', isset($id) ? ['' => '', optional($data->category)->id => optional($data->category)->name] : [], old('category_id'))
                                        ->class('select2js form-group')
                                        ->id('category_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.category')]))
                                        ->attribute('data-allow-clear', 'true')
                                    }}
                                </div>

                                <div class="form-group col-md-6 d-none" id="for_story_image">
                                   <label class="form-control-label" for="image">{{ __('message.story_image') }} </label>
                                   <div class="custom-file">
                                       <input type="file" class="custom-file-input" style="line-height: 20px;" name="story_image[]"  multiple>
                                       <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.story_image') ]) }}</label>
                                   </div>
                                </div>
                                <div class="form-group col-md-6 d-none" id="video_upload">
                                    {{ html()->label(__('message.video'))->class('form-control-label')->attribute('for', 'video_id')}}
                                    {{ html()->select('video_id', isset($id) ? [optional($data->video)->id => optional($data->video)->title] : [], old('video_id'))
                                        ->class('select2js form-group')
                                        ->id('video_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.video')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'video_data']))
                                        ->attribute('data-allow-clear', 'true')
                                    }}
                                </div>

                                <div class="form-group col-md-{{ isset($id) && getMediaFileExit($data, 'image_video_image') ? 4 : 6 }} d-none view_type_image_video_image" id="">
                                   <label class="form-control-label" for="image">{{ __('message.image') }} </label>
                                   <div class="custom-file">
                                       <input type="file" name="image_video_image" class="custom-file-input" accept="image/*">
                                       <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                   </div>
                                   <!-- <span class="selected_file"></span> -->
                                </div>

                                @if( isset($id) && getMediaFileExit($data, 'image_video_image'))
                                   <div class="col-md-2 mb-2 d-none view_type_image_video_image">
                                       <img id="image_video_image_preview" src="{{ getSingleMedia($data,'image_video_image') }}" alt="image_video_image-image" class="attachment-image mt-1">
                                       <a class="text-danger remove-file ml-1" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'image_video_image']) }}"
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

                                <div class="form-group col-md-{{ isset($id) && getMediaFileExit($data, 'video_image_video') ? 4 : 6 }} d-none view_type_video_image_video" id="view_type_video_image-video">
                                   <label class="form-control-label" for="image">{{ __('message.video') }} </label>
                                   <div class="custom-file">
                                       <input type="file" name="video_image_video" class="custom-file-input" accept="video/*">
                                       <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.video') ]) }}</label>
                                   </div>
                                   <!-- <span class="selected_file"></span> -->
                                </div>

                                @if( isset($id) && getMediaFileExit($data, 'video_image_video'))
                                   <div class="col-md-2 mb-2 d-none view_type_video_image_video">
                                       <a href="{{ getSingleMedia($data , 'video_image_video') }}" class="popup-vimeo" id="video_image_video_preview"><video class="video_image_video_preview" src="{{ getSingleMedia($data , 'video_image_video') }}" id="" width="85%" height="100" ></video></a>
                                       <a class="text-danger remove-file ml-1 mt-1" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'video_image_video']) }}"
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
                                    {{ html()->label(__('message.article') . ' <span class="text-danger">*</span>', 'article_id')->class('form-control-label') }}
                                    {{ html()->select('article_id', $selected_article ?? [])
                                        ->class('select2js form-group')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article'])) 
                                    }}
                                </div>
                                <div class="form-group col-md-6 d-none" id="blog_course_upload">
                                    {{ html()->label(__('message.blog_course') . ' <span class="text-danger">*</span>', 'blog_course_article_id')->class('form-control-label') }}
                                    {{ html()->select('blog_course_article_id[]', [], $selected_blog_course_article)
                                        ->class('select2js form-group tags')
                                        ->multiple()
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.blog_course')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article'])) 
                                    }}
                                </div>


                                <div class="form-group col-md-6">
                                  {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('status')}}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js') }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ html()->label(__('message.users') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('user_list')}}
                                    {{ html()->select('users[]', $client, null)
                                        ->id('user_list')
                                        ->class('select2js form-control')
                                        ->multiple()
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.users')]) . 
                                            (isset($selectedUserIds) && count($selectedUserIds) > 0 ? ' (' . count($selectedUserIds) . ')' : '')
                                        )
                                    }}
                                </div>

                                <div class="form-group col-md-2">
                                   <div class="custom-control custom-checkbox mt-4 pt-3">
                                       <input type="checkbox" class="custom-control-input selectAll" id="all_user" data-usertype="client">
                                       <label class="custom-control-label" for="all_user">{{ __('message.select_all') }}</label>
                                   </div>
                                </div>

                                <div class="form-group col-md-4">
                                    {{ html()->label(__('message.anonymous_user') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('anonymous_list') }}
                                    {{ html()->select('anonymous_user[]', $anonymous_user, null)
                                        ->class('select2js form-control')
                                        ->id('anonymous_list')
                                        ->multiple()
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.anonymous_user')]) . 
                                            (isset($selectedAnonymousIds) && count($selectedAnonymousIds) > 0 ? ' (' . count($selectedAnonymousIds) . ')' : '')
                                        )
                                    }}
                                </div>

                                <div class="form-group col-md-2">
                                   <div class="custom-control custom-checkbox mt-4 pt-3">
                                       <input type="checkbox" class="custom-control-input selectAll" id="all_anonymous_user" data-usertype="client">
                                       <label class="custom-control-label" for="all_anonymous_user">{{ __('message.select_all') }}</label>
                                   </div>
                                </div>
                               
                                <div class="form-group col-md-12" id="for_story_image">
                                    @if(isset($id) && getMediaFileExit($data, 'story_image'))
                                        @php
                                            $images = $data->getMedia('story_image');
                                            $file_extensions = config('constant.IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
                                        @endphp
                                        <div>
                                            @if (!empty($images) && count($images) > 0)
                                                <div class="row">
                                                    @foreach($images as $image)
                                                        @php
                                                            $fileUrl = $image->getFullUrl();
                                                            $imageExtension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION)); 
                                                            $isImage = in_array($imageExtension, $file_extensions);
                                                        @endphp
                                                        <div class="col-md-1 pr-0 mb-3"> 
                                                            <a href="{{ $isImage ? $image->getUrl() : asset('images/file.png') }}" class="magnific-popup-image-gallery avatar-100">
                                                                <img id="{{ $image->id }}_preview" 
                                                                    src="{{ $isImage ? $image->getUrl() : asset('images/file.png') }}" 
                                                                    alt="{{ $image->name }}" 
                                                                    class="avatar-100 card-img-top">
                                                            </a>
                                                        </div>      
                                                    @endforeach
                                                </div>  
                                            @endif
                                        </div>
                                    @endif
                                </div>
                               
                                <div class="form-group col-md-12" id="video_upload">
                                   @if( isset($id) && getMediaFileExit($data, 'insights_video'))
                                       <div class="col-md-2 mb-2 position-relative">
                                           <?php
                                               $file_extention = config('constant.VIDEO_EXTENSIONS');
                                               $video = getSingleMedia($data, 'insights_video');
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
                                               href="{{ route('remove.file', ['id' => $data->id, 'type' => 'insights_video']) }}"
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
                            <div class="row">
                                <div class="form-group d-flex align-items-center col-md-2">
                                    <div class="custom-switch custom-switch-text custom-switch-color custom-control-inline">
                                        <div class="custom-switch-inner">
                                            <input type="checkbox" class="custom-control-input bg-success change_status" data-type="user" id="switch1"
                                                @if(isset($data) && $data->view_all_users) checked @endif>
                                            <label class="custom-control-label" for="switch1" data-on-label="Yes" data-off-label="No"></label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="view_all_users" value="0"> 
                                    <h6 class="my-4 ml-3">View All Users</h6>
                                </div>
                                <div class="form-group d-flex align-items-center col-md-2">
                                    <div class="custom-switch custom-switch-text custom-switch-color custom-control-inline">
                                        <div class="custom-switch-inner">
                                            <input type="checkbox" class="custom-control-input bg-success change_status" data-type="user"  id="switch2"
                                                    @if(isset($data) && $data->view_all_anonymous_users) checked @endif>
                                            <label class="custom-control-label" for="switch2" data-on-label="Yes" data-off-label="No"></label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="view_all_anonymous_users" value="0"> 
                                    <h6 class="my-4 ml-3">View All Anonymous Users</h6>
                                </div>
                            </div>
                            <div class="row" id="viewTypeDiv" style="display: none;">
                                <div class="col-md-12">
                                    <button type="button" id="add_button" class="btn btn-sm btn-primary float-right mb-3">{{ __('message.add') }}</button>
                                    <table class="table table-bordered" id="insghts">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('message.title')}}</th>
                                                <th>{{__('message.description')}}</th>
                                                <th>{{__('message.bg_color')}}</th>
                                                <th>{{__('message.text_color')}}</th>
                                                <th>{{__('message.action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_list">
                                            @if(isset($data->personalinsights_data) && count($data->personalinsights_data) > 0)
                                                @foreach($data->personalinsights_data as $key => $item)
                                                    <tr id="row_{{ $key }}" row="{{ $key }}" data-id="0">
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ html()->text("title_name[$key]", $item['title_name'])->class('form-control mb-2')->placeholder('Title') }}</td>
                                                        <td>{{ html()->textarea("description[$key]", $item['description'])->class('form-control')->placeholder('Description')->rows(2)->cols(40) }}</td>
                                                        <td>{{ html()->input('color', "bg_color[$key]", $item['bg_color'])->class('form-control mb-2') }}</td>
                                                        <td>{{ html()->input('color', "text_color[$key]", $item['text_color'])->class('form-control') }}</td>
                                                        <td>
                                                            <a href="javascript:void(0)" id="remove_{{ $key }}" class="removebtn btn btn-sm btn-danger" row="{{ $key }}"> <i class="fas fa-trash-alt"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr id="row_0" row="0" data-id="0">
                                                    <td>1</td>
                                                    <td>{{ html()->text("title_name[0]")->class('form-control mb-2')->placeholder('Title') }}</td>
                                                    <td>{{ html()->textarea("description[0]")->class('form-control')->placeholder('Description')->rows(2)->cols(40) }}</td>
                                                    <td>{{ html()->input('color', "bg_color[0]")->class('form-control mb-2') }}</td>
                                                    <td>{{ html()->input('color', "text_color[0]")->class('form-control') }}</td>
                                                    <td>
                                                        <a href="javascript:void(0)" id="remove_0" class="removebtn btn btn-sm btn-danger" row="0"> <i class="fas fa-trash-alt"></i></a>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
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
            $(document).ready(function () {
                document.querySelectorAll('.change_status').forEach(item => {
                    item.addEventListener('change', function () {

                        let hiddenInput = this.closest('.form-group').querySelector('input[type="hidden"]');

                        
                        if (this.checked) {
                            hiddenInput.value = '1'; 
                        } else {
                            hiddenInput.value = '0'; 
                        }
                    });
                });

                function toggleFields(switchId, fieldIds) {
                    if ($(switchId).is(':checked')) {
                        fieldIds.forEach(id => $(id).closest('.form-group').hide());
                    } else {
                        fieldIds.forEach(id => $(id).closest('.form-group').show());
                    }
                }

                toggleFields('#switch1', ['#user_list', '#all_user']);
                toggleFields('#switch2', ['#anonymous_list', '#all_anonymous_user']);

                $('#switch1').change(function () {
                    toggleFields('#switch1', ['#user_list', '#all_user']);
                });

                $('#switch2').change(function () {
                    toggleFields('#switch2', ['#anonymous_list', '#all_anonymous_user']);
                });

                function updateInsightsType() {
                    var goalType = $('#goal_type_id').val();
                    var $insightsType = $('select[name="insights_type"]');

                    if (goalType == '1') {
                        $insightsType.val('0').trigger('change'); 
                        $insightsType.prop('disabled', true); 
                    } else {
                        $insightsType.prop('disabled', false); 
                    }
                }
        
                updateInsightsType();

                $('#goal_type_id').on('change', function() {
                    updateInsightsType();
                });
                var isImageUploaded = {{ isset($id) && getMediaFileExit($data, 'thumbnail_image') ? 'true' : 'false' }};
                var anonymousUserCount = {{ isset($selectedAnonymousIds) ? count($selectedAnonymousIds) : 0 }}; // Get the count
                var UserCount = {{ isset($selectedUserIds) ? count($selectedUserIds) : 0 }}; // Get the count

                formValidation("#personalinsights_validation_form", {
                    title: { required: true },
                    thumbnail_image: { required: !isImageUploaded },
                    goal_type: { required: true },
                    view_type: { required: true },
                    insights_type: { required: true },
                    "users[]": { required: UserCount === 0 },
                    "anonymous_user[]": { required: anonymousUserCount === 0 } 
                }, {
                    title: { required: "Please enter a Title." },
                    thumbnail_image: { required: "Please select a Thumbnail Image." },
                    goal_type: { required: "Please select a goal type." },
                    view_type: { required: "Please select a View type." },
                    insights_type: { required: "Please select a Insights use for." },
                    "users[]": { required: "Please select a user." },
                    "anonymous_user[]": { required: "Please select an Anonymous User." }
                });
                
            });
            function validateInsightFields() {
                var isValid = true;

                $('#table_list tr').each(function () {
                    var row = $(this);
                    var rowIndex = row.attr('row');

                    // Validate Title
                    var title = row.find('[name="title_name[' + rowIndex + ']"]');
                    if ($.trim(title.val()) === '') {
                        title.addClass('is-invalid');
                        isValid = false;
                    } else {
                        title.removeClass('is-invalid');
                    }

                    // Validate Description
                    var desc = row.find('[name="description[' + rowIndex + ']"]');
                    if ($.trim(desc.val()) === '') {
                        desc.addClass('is-invalid');
                        isValid = false;
                    } else {
                        desc.removeClass('is-invalid');
                    }

                    // Optional: Validate colors
                    var bgColor = row.find('[name="bg_color[' + rowIndex + ']"]');
                    var textColor = row.find('[name="text_color[' + rowIndex + ']"]');
                    if (bgColor.val() === '') {
                        bgColor.addClass('is-invalid');
                        isValid = false;
                    } else {
                        bgColor.removeClass('is-invalid');
                    }
                    if (textColor.val() === '') {
                        textColor.addClass('is-invalid');
                        isValid = false;
                    } else {
                        textColor.removeClass('is-invalid');
                    }

                });

                return isValid;
            }

        </script>
        <script>
            (function ($) {
                $(document).ready(function () {
                    // var symptomsData = "{{ old('sub_symptoms_id') ?? (isset($data) ? $data->sub_symptoms_id : '') }}";
                    
                    // symptom_data($('#symptoms_id').val(), symptomsData);

                    // $(document).on('change', '#symptoms_id', function () {
                    //     var get_symptoms_id = $(this).val();
                    //     symptom_data(get_symptoms_id);
                    // });

                    // function symptom_data(get_symptoms_id, selectedSubSymptomId = null) {
                    //     var symptoms_url = "{{ route('ajax-list', ['type' => 'get_symptoms_category',  'symptoms_id' => '']) }}" + get_symptoms_id;
                    //     symptoms_url = symptoms_url.replace('amp;', '');

                    //     $.ajax({
                    //         url: symptoms_url,
                    //         success: function (result) {
                    //             if (result.results) {
                    //                 var subSymptomsID = $('#sub_symptoms_id');
                    //                 subSymptomsID.empty();
                    //                 subSymptomsID.select2({
                    //                     width: '100%',
                    //                     placeholder: "{{ __('message.select_name', ['select' => __('message.symptoms')]) }}",
                    //                     data: result.results
                    //                 });

                    //                 if (selectedSubSymptomId !== null) {
                    //                     subSymptomsID.val(selectedSubSymptomId).trigger('change');
                    //                 }
                    //             } else {
                    //                 console.log("No results found.");
                    //             }
                    //         }
                    //     });
                    // }

                    var categoryData = "{{ old('goal_type') ?? (isset($data) ? $data->goal_type : '') }}";
                    
                    category_data($('#category_id').val(), categoryData);

                    $(document).on('change', '#goal_type_id', function () {

                        var get_goal_type_id = $(this).val();
                        category_data(get_goal_type_id);
                    });

                    function category_data(get_goal_type_id) {
                        var cat_url = "{{ route('ajax-list', ['type' => 'get_category_by_goal_type',  'goal_type' => '']) }}" + get_goal_type_id;
                        cat_url = cat_url.replace('amp;', '');

                        $.ajax({
                            url: cat_url,
                            success: function (result) {
                                if (result.results) {
                                    var categoryID = $('#category_id');
                                    categoryID.empty();
                                    categoryID.select2({
                                        width: '100%',
                                        placeholder: "{{ __('message.select_name', ['select' => __('message.symptoms')]) }}",
                                        data: result.results
                                    });

                                } else {
                                    console.log("No results found.");
                                }
                            }
                        });
                    }

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
                });
            })(jQuery);
        </script>
        <script>
            $(document).ready(function() {
                $('.select2js').select2();

                function updateClientCounter() {
                    let count = $('#user_list').select2('data').length;
                    $('#user_list').next('span.select2').find('ul').html("<li class='ml-2'>" + count + " User Selected</li>");
                }

                function updateAnonymousCounter() {
                    let count = $('#anonymous_list').select2('data').length;
                    $('#anonymous_list').next('span.select2').find('ul').html("<li class='ml-2'>" + count + " Anonymous Selected</li>");
                }

                $('#all_user').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#user_list').find('option').prop('selected', true);
                    } else {
                        $('#user_list').find('option').prop('selected', false);
                    }
                    $('#user_list').trigger('change');
                    updateClientCounter();
                });

                $('#all_anonymous_user').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#anonymous_list').find('option').prop('selected', true);
                    } else {
                        $('#anonymous_list').find('option').prop('selected', false);
                    }
                    $('#anonymous_list').trigger('change');
                    updateAnonymousCounter();
                });

                $('#user_list').on('change', function() {
                    updateClientCounter();
                });

                $('#anonymous_list').on('change', function() {
                    updateAnonymousCounter();
                });

                updateClientCounter();
                updateAnonymousCounter();

                $(document).on('change', '#view_type_id', function () {
                    var view_id = $(this).val();
                    
                    if (view_id == 7) {
                        console.log(view_id); 
                        $('#viewTypeDiv').show();
                    } else {
                        $('#viewTypeDiv').hide();
                        clearFields();
                    }
                });

                $('#view_type_id').trigger('change');

                function clearFields() {
                    $('#viewTypeDiv input').each(function() {
                        $(this).val(''); 
                    });
                    $('#viewTypeDiv select').each(function() {
                        $(this).val('').trigger('change'); 
                    });
                }
                        
            });
        </script>
    @endsection
</x-master-layout>

