<x-master-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('symptoms.update', $id))->attribute('enctype', 'multipart/form-data')->id('symptoms_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('symptoms.store'))->attribute('enctype','multipart/form-data')->id('symptoms_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('symptoms.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('title')}}
                                    {{ html()->text('title')->placeholder(__('message.title'))->class('form-control') }}
                                </div>
                            
                                <div class="form-group col-md-3">
                                    {{ html()->label(__('message.bg_color') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('bg_color_picker') }}
                                    {{ html()->input('color', 'bg_color')->class('form-control')->required()->id('bg_color_picker') }}
                                </div>
                                <div class="form-group col-md-3">
                                    {{ html()->label(__('message.bg_color') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('bg_color_code')}}
                                    {{ html()->text('bg_color')->placeholder(__('message.bg_color'))->class('form-control')->required()->id('bg_color_code')}}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ html()->hidden('article_id') }}
                                    {{ html()->label(__('message.article'))->class('form-control-label')->for('article_id') }}
                                    {{ html()->select('article_id', isset($id) ? [optional($data->article)->id => optional($data->article)->name] : [], old('article_id'))
                                        ->class('select2js form-group')
                                        ->id('article_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article']))
                                        ->attribute('data-allow-clear', 'true')
                                    }}
                                </div>
                                
                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>')->class('form-control-label')->for('status')}}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js')->required() }}
                                </div>
                            </div>
                            <hr>
                            {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right')}}
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
                formValidation("#symptoms_validation_form", {
                    title: { required: true },
                    bg_color: { required: true },

                }, {
                    title: { required: "Please enter a Title." },
                    bg_color: { required: "Please select a Background color."},
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                function colorCodeInput() {
                    var colorCode = $('#bg_color_code').val();
                    if (colorCode[0] !== '#') {
                        colorCode = '#' + colorCode;
                    }
                    $('#bg_color_code').val(colorCode);
                }
            
                $('#bg_color_code').on('input', function() {
                    colorCodeInput();
                    var colorCode = $(this).val();
                    $('#bg_color_picker').val(colorCode);
                });
            
                $('#bg_color_picker').on('input', function() {
                    var selectedColor = $(this).val();
                    $('#bg_color_code').val(selectedColor);
                });
            
                colorCodeInput();
            });
        </script>
    @endsection
</x-master-layout>
