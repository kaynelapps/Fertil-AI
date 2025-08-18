{{ html()->modelForm($setting_value, 'POST' , route('settingUpdate'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}

    {{ html()->hidden('id', null, ['class' => 'form-control'] ) }}
    {{ html()->hidden('page', $page, ['class' => 'form-control'] ) }}
    <div class="row">
        @foreach($setting as $key => $value)
        @if($key === 'APPVERSION')
            @continue
        @endif
            <div class="col-md-12 col-sm-12 card shadow mb-10">
                <div class="card-header">
                    <h4>{{ str_replace('_',' ',$key) }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($value as $sub_keys => $sub_value)
                            @php
                                $data=null;
                                foreach($setting_value as $v){

                                    if($v->key==($key.'_'.$sub_keys)){
                                        $data = $v;
                                    }
                                }
                                $class = 'col-md-6';
                                $type = 'text';
                                switch ($key){
                                    case 'FIREBASE':
                                        $class = 'col-md-12';
                                        break;
                                    case 'COLOR' :
                                        $type = 'color';
                                        break;
                                    default : break;
                                }
                            @endphp
                            <div class=" {{ $class }} col-sm-12">
                                <div class="form-group">
                                        <label for="{{ $key.'_'.$sub_keys }}">{{ str_replace('_',' ',$sub_keys) }} </label>
                                    {{ html()->hidden('type[]', $key)->class('form-control') }}
                                    <input type="hidden" name="key[]" value="{{ $key.'_'.$sub_keys }}">

                                    @if($key == 'FEATURE' && $sub_keys == 'ASK_EXPERT')
                                            {{ html()->select('value[]', ['1' => __('message.yes'), '0' => __('message.no')], isset($data) ? $data->value : '1')->class('form-control select2js') }}
                                    @elseif($key == 'FEATURE' && $sub_keys == 'ADD_DUMMY_DATA')
                                            {{ html()->select('value[]', ['1' => __('message.yes'), '0' => __('message.no')], isset($data) ? $data->value : '1')->class('form-control select2js') }}
                                    @elseif($key == 'CHATGPT')
                                        @if($sub_keys == 'API_KEY')
                                            <input type="text" name="value[]" value="{{ $data->value ?? '' }}" id="{{ $key . '_' . $sub_keys }}"   class="form-control"   placeholder="Enter Website ID">
                                        @elseif($sub_keys == 'ENABLE/DISABLE')
                                            <div class="col-sm-4">
                                                <div class="custom-control custom-checkbox custom-control-inline col-2">
                                                    {{ html()->checkbox('value[]', empty($data) || $data->value == '1', '1')
                                                        ->class('custom-control-input')
                                                        ->id($key . '_yes')
                                                        ->attribute('onclick', "toggleCheckbox('{$key}_yes', '{$key}_no')") }}
                                                    {{ html()->label(__('message.yes'), $key . '_yes')->class('custom-control-label') }}
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline col-2">
                                                    {{ html()->checkbox('value[]', !empty($data) && $data->value == '0', '0')
                                                        ->class('custom-control-input')
                                                        ->id($key . '_no')
                                                        ->attribute('onclick', "toggleCheckbox('{$key}_no', '{$key}_yes')") }}
                                                    {{ html()->label(__('message.no'), $key . '_no')->class('custom-control-label') }}
                                                </div>
                                            </div>
                                        @endif
                                    @elseif($key == 'CRISP_CHAT_CONFIGURATION')
                                        @if ($sub_keys === 'WEBSITE_ID')
                                            <input type="text" name="value[]" value="{{ $data->value ?? '' }}" id="{{ $key . '_' . $sub_keys }}"   class="form-control"   placeholder="Enter Website ID">
                                        @elseif ($sub_keys === 'ENABLE/DISABLE')
                                            <div class="col-sm-4">
                                                <div class="custom-control custom-checkbox custom-control-inline col-2">
                                                    {!! html()->checkbox("value[]", empty($data) || $data->value == '1', '1')
                                                        ->class('custom-control-input')
                                                        ->id($key . '_yes')
                                                        ->attribute('onclick', "toggleCheckbox('{$key}_yes', '{$key}_no')") !!}
                                                    {!! html()->label(__('message.yes'))
                                                        ->for($key . '_yes')
                                                        ->class('custom-control-label') !!}
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline col-2">
                                                    {{ html()->checkbox("value[]", !empty($data) && $data->value == '0', '0')->class('custom-control-input')->id($key . '_no')->attribute('onclick', "toggleCheckbox('{$key}_no', '{$key}_yes')") }}
                                                    {{ html()->label(__('message.no'))->for($key . '_no')->class('custom-control-label') }}
                                                </div>
                                            </div>
                                            @endif
                                    @else
                                        <input type="{{ $type }}" name="value[]" value="{{ isset($data) ? $data->value : null }}" id="{{ $key.'_'.$sub_keys }}" {{ $type == 'number' ? "min=0 step='any'" : '' }} class="form-control form-control-lg" placeholder="{{ str_replace('_',' ',$sub_keys) }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        @if($key == 'CRISP_CHAT_CONFIGURATION')
                            <div class="form-group col-md-4">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="icon_image" name="icon_image" accept="image/*">
                                    <label class="custom-file-label" for="icon_image">
                                        {{ __('message.choose_file', ['file' => __('message.icon_image')]) }}
                                    </label>
                                </div>
                            </div>

                            <div class="card-body">
                                @if(isset($image) && $image->hasMedia('icon_image'))
                                    <div class="row">
                                        <div id="image-preview" class="d-flex flex-wrap col-md-12">
                                            @foreach($image->getMedia('icon_image') as $media)
                                                @php
                                                    $file_extensions = config('constant.IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
                                                    $fileUrl = $media->getFullUrl();
                                                    $imageExtension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));
                                                    $isImage = in_array($imageExtension, $file_extensions);
                                                @endphp
                                                <div class="col-md-2 mb-2">
                                                    <a href="{{ $isImage ? $media->getUrl() : asset('images/file.png') }}" class="magnific-popup-image-gallery">
                                                        <img id="{{ $media->id }}_preview"
                                                            src="{{ $isImage ? $media->getUrl() : asset('images/file.png') }}"
                                                            alt="{{ $media->name }}"
                                                            class="avatar-100 card-img-top">
                                                    </a>
                                                    <a class="text-danger remove-file mt-3 ml-1"
                                                        href="{{ route('remove.file', ['id' => $media->id, 'type' =>'icon_image']) }}"
                                                        data--submit='confirm_form' data--confirmation='true' data--ajax='true'
                                                        data-toggle='tooltip'
                                                        title='{{ __("message.remove_file_title" , ["name" => __("message.image") ]) }}'
                                                        data-title='{{ __("message.remove_file_title" , ["name" => __("message.image") ]) }}'
                                                        data-message='{{ __("message.remove_file_msg") }}'>
                                                        <i class="ri-close-circle-line"></i>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="col-md-12">
                            {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary') }}
                        </div>
                    </div>
                </div>
            </div>
        @endForeach
    </div>
{{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right') }}
{{ html()->form()->close() }}

<script>
    $(document).ready(function() {
        $(".app-version-input").on("input", function () {
        let regex = /^\d+(\.\d+){0,2}$/;
        let value = $(this).val();

        if (!regex.test(value)) {
            $(this).val(value.replace(/[^0-9]/g, ''));
        }
    });
        $('.select2js').select2();
    });
    function toggleCheckbox(selectedId, otherId) {
        if ($('#' + selectedId).is(':checked')) {
            $('#' + otherId).prop('checked', false);
        }
    }

    if( $('.magnific-popup-image-gallery').length > 0 ){
            $('.magnific-popup-image-gallery').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                closeBtnInside: false,
                mainClass: 'mfp-with-zoom',
                gallery: {
                    enabled: true
                },
                zoom: {
                    enabled: true,
                    duration: 350,
                    easing: 'ease-in-out',
                    opener: function(openerElement) {
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }
            });
        }
</script>
