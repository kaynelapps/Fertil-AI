<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('insights.update', $id))->attribute('enctype', 'multipart/form-data')->id('insights_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('insights.store'))->attribute('enctype','multipart/form-data')->id('insights_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('insights.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>')->class('form-control-label')->attribute('for', 'title') }}
                                    {{ html()->text('title')->class('form-control')->placeholder(__('message.title')) }}
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
                                    {{ html()->label(__('message.sub_symptoms'). ' <span class="text-danger">*</span>', 'sub_symptoms_id')->class('form-control-label')->attribute('for', 'sub_symptoms_id') }}
                                    {{ html()->select('sub_symptoms_id', isset($id) ? [optional($data->subSymptoms)->id => optional($data->subSymptoms)->title] : [])
                                        ->class('select2js form-group')
                                        ->id('sub_symptoms_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.sub_symptoms')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'sub_symptoms_category']))
                                        ->attribute('data-allow-clear', 'true') 
                                    }}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                                    {{ html()->select('goal_type', ['' => ''] + getGoalType(), $data->type ?? old('goal_type'))
                                        ->class('select2js form-group type')
                                        ->id('goal_type_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))
                                        ->attribute('data-allow-clear', 'true')
                                    }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.insights_use_for') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
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
                                    {{ html()->label(__('message.category'))->class('form-control-label') }}
                                    {{ html()->select('category_id', isset($id) ? ['' => '', optional($data->category)->id => optional($data->category)->name] : [])
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
                                    {{ html()->label(__('message.video'))->class('form-control-label')->attribute('for', 'video_id') }}
                                    {{ html()->select('video_id', isset($id) ? [optional($data->video)->id => optional($data->video)->title] : [])
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
                                
                                    <div class="form-group col-md-6">
                                        <input type="hidden" name="article_id">
                                        {{ html()->label(__('message.article'))->class('form-control-label')->attribute('for', 'article_id') }}
                                        {{ html()->select('article_id', isset($id) ? [optional($data->article)->id => optional($data->article)->name] : [])
                                            ->class('select2js form-group')
                                            ->id('article_id')
                                            ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                            ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article']))
                                            ->attribute('data-allow-clear', 'true') 
                                        }}
                                    </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')])->class('form-control select2js') }}
                                </div>

                                <div class="form-group col-md-12" id="for_story_image">
                                    @if(isset($id) && getMediaFileExit($data, 'story_image'))
                                        <h5>{{ __('message.story_image') }} :</h5>
                                        @php
                                            $images = $data->getMedia('story_image');
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
                                                                        @if(in_array($image->mime_type, ['image/jpeg', 'image/png', 'image/gif','image/avif']))
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
                                            @if(isset($data->insights_data) && count($data->insights_data) > 0)
                                                @foreach($data->insights_data as $key => $item)
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
            $(document).ready(function(){
                var isImageUploaded = {{ isset($id) && getMediaFileExit($data, 'thumbnail_image') ? 'true' : 'false' }};
                formValidation("#insights_validation_form", {
                    title: { required: true },
                    thumbnail_image: { required: !isImageUploaded },
                    goal_type: { required: true },
                    view_type: { required: true },
                    insights_type: { required: true },
                    sub_symptoms_id: { required: true },
                    

                }, {
                    title: { required: "Please enter a Title." },
                    thumbnail_image: { required: "Please select a Thumbnail Image."},
                    goal_type: { required: "Please select a goal type."},
                    view_type: { required: "Please select a View type."},
                    insights_type: { required: "Please select a Insights use for."},
                    sub_symptoms_id: { required: "Please select a sub symptoms."},

                });
            });
        </script>
        <script>
            (function ($) {
                $(document).ready(function () {
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

                    $(document).on('change', '#view_type_id', function () {
                        var view_id = $(this).val();
                        $("#for_story_image").toggleClass('d-none', view_id != 0);
                        $("#video_upload").toggleClass('d-none', view_id != 1);
                        $("#for_cateogry").toggleClass('d-none', view_id != 2);
                        $(".view_type_image_video_image").toggleClass('d-none', view_id != 3);
                        $(".view_type_video_image_video").toggleClass('d-none', view_id != 3);
                
                        if (view_id == 2) {
                            $("#goal_type_id").trigger('change');
                        }
                    });
                
                    var view_type_data = "{{ old('view_type_id') ?? (isset($data) ? $data->view_type : '') }}";
                    var category_id = "{{ old('category_id') ?? (isset($data) ? $data->category_id : '') }}";
                
                    if (view_type_data == 0 && view_type_data != '') {
                        $("#for_story_image").removeClass('d-none');
                    } else if (view_type_data == 1 && view_type_data != '') {
                        $("#video_upload").removeClass('d-none');
                    } else if (view_type_data == 2 && view_type_data != '') {
                        $("#for_cateogry").removeClass('d-none');
                        $("#goal_type_id").trigger('change');
                    }else if (view_type_data == 3 && view_type_data != '') {
                        $(".view_type_image_video_image").removeClass('d-none');
                        $(".view_type_video_image_video").removeClass('d-none');
                    }
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

                    // Trigger change on page load
                    $('#view_type_id').trigger('change');

                    function clearFields() {
                        $('#viewTypeDiv input').each(function() {
                            $(this).val(''); 
                        });
                        $('#viewTypeDiv select').each(function() {
                            $(this).val('').trigger('change'); 
                        });
                    }
                    
                    $('#add_button').on('click', function () {
                        var tableBody = $('#insghts').find('tbody');
                        var lastRow = tableBody.find('tr:last');
                        var newRow = lastRow.clone();

                        var lastRowId = parseInt(lastRow.attr('row'));
                        var newRowId = lastRowId + 1;

                        newRow.attr('id', 'row_' + newRowId)
                            .attr('row', newRowId)
                            .attr('data-id', 0);

                        newRow.find('input').each(function () {
                            var nameAttr = $(this).attr('name');
                            if (nameAttr) {
                                var updatedName = nameAttr.replace(/\[\d+\]/, '[' + newRowId + ']');
                                $(this).attr('name', updatedName);
                            }
                            $(this).val('');
                        });

                        newRow.find('textarea').each(function () {
                            var nameAttr = $(this).attr('name');
                            if (nameAttr) {
                                var updatedName = nameAttr.replace(/\[\d+\]/, '[' + newRowId + ']');
                                $(this).attr('name', updatedName);
                            }
                            $(this).val('');
                        });

                        lastRow.after(newRow);
                    });

                    $('#insghts').on('click', '.removebtn', function () {
                        var rowCount = $('#table_list tr').length;
                        if (rowCount > 1) {
                            $(this).closest('tr').remove();
                        } else {
                            alert('You cannot delete the last row.');
                        }
                    });
                });
            })(jQuery);
        </script>
    @endsection
</x-master-layout>
