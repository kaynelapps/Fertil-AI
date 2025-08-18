<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('category.update', $id))->attribute('enctype', 'multipart/form-data')->id('category_form')->open() }}
        @else
            {{ html()->form('POST', route('category.store'))->attribute('enctype','multipart/form-data')->id('category_form')->open() }}  
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('category.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                                    {{ html()->text('name')->placeholder(__('message.name'))->class('form-control')->required() }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js')->required() }}
                                </div>

                                @if(isset($id) && getMediaFileExit($data, 'category_thumbnail_image'))
                                    <div class="form-group col-md-5">
                                        {{ html()->label(__('message.thumbnail_image'), 'category_thumbnail_image')->class('form-control-label') }}
                                        <div class="custom-file">
                                            {{ html()->file('category_thumbnail_image')->class('custom-file-input')->accept('image/*') }}
                                            {{ html()->label(__('message.choose_file', ['file' => __('message.image')]))->class('custom-file-label') }}
                                        </div>
                                        <span class="selected_file"></span>
                                    </div>

                                    <div class="col-md-1 mb-2">
                                        <img id="category_thumbnail_image_preview" src="{{ getSingleMedia($data,'category_thumbnail_image') }}" alt="category-image" class="attachment-image mt-2">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'category_thumbnail_image']) }}"
                                        data--submit="confirm_form" data--confirmation="true" data--ajax="true" data-toggle="tooltip"
                                        title="{{ __('message.remove_file_title', ['name' => __('message.image')]) }}"
                                        data-title="{{ __('message.remove_file_title', ['name' => __('message.image')]) }}"
                                        data-message="{{ __('message.remove_file_msg') }}">
                                            <i class="ri-close-circle-line"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="form-group col-md-6">
                                        {{ html()->label(__('message.thumbnail_image'), 'category_thumbnail_image')->class('form-control-label') }}
                                        <div class="custom-file">
                                            {{ html()->file('category_thumbnail_image')->class('custom-file-input')->accept('image/*') }}
                                            {{ html()->label(__('message.choose_file', ['file' => __('message.image')]))->class('custom-file-label') }}
                                        </div>
                                        <span class="selected_file"></span>
                                    </div>
                                @endif

                                @if(isset($id) && getMediaFileExit($data, 'header_image'))
                                    <div class="form-group col-md-5">
                                        {{ html()->label(__('message.header_image'), 'header_image')->class('form-control-label') }}
                                        <div class="custom-file">
                                            {{ html()->file('header_image')->class('custom-file-input')->accept('image/*') }}
                                            {{ html()->label(__('message.choose_file', ['file' => __('message.header_image')]))->class('custom-file-label') }}
                                        </div>
                                        <span class="selected_header_file"></span>
                                    </div>

                                    <div class="col-md-1 mb-2">
                                        <img id="category_header_image_preview" src="{{ getSingleMedia($data,'header_image') }}" alt="header-image" class="attachment-image mt-1">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'header_image']) }}"
                                        data--submit="confirm_form" data--confirmation="true" data--ajax="true" data-toggle="tooltip"
                                        title="{{ __('message.remove_file_title', ['name' => __('message.image')]) }}"
                                        data-title="{{ __('message.remove_file_title', ['name' => __('message.image')]) }}"
                                        data-message="{{ __('message.remove_file_msg') }}">
                                            <i class="ri-close-circle-line"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="form-group col-md-6">
                                        {{ html()->label(__('message.header_image'), 'header_image')->class('form-control-label') }}
                                        <div class="custom-file">
                                            {{ html()->file('header_image')->class('custom-file-input')->accept('image/*') }}
                                            {{ html()->label(__('message.choose_file', ['file' => __('message.header_image')]))->class('custom-file-label') }}
                                        </div>
                                        <span class="selected_header_file"></span>
                                    </div>
                                @endif

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>', 'goal_type')->class('form-control-label')}}
                                    {{ html()->select('goal_type', ['' => ''] + getGoalType(), $data->type ?? old('goal_type'))
                                        ->class('select2js form-group type')
                                        ->id('goal_type_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))
                                        ->attribute('data-allow-clear', 'true')
                                        ->required() }}
                                </div>

                                <div class="form-group col-md-12">
                                    {{ html()->label(__('message.description'), 'description')->class('form-control-label') }}
                                    {{ html()->textarea('description')->class('form-control tinymce-description')->placeholder(__('message.description'))->rows(3) }}
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
                formValidation("#category_form", {
                    name: { required: true },
                    goal_type: { required: true }
                }, {
                    name: { required: "Please enter a Name." },
                    goal_type: { required: "Please select a goal type."}
                });
            });
        </script>
    @endsection

</x-master-layout>
