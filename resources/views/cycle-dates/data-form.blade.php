<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                            <div class="float-right">
                                <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('message.back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-block">
                    <div class="card-body">
                        @if(isset($id))
                            {{ html()->modelForm($data, 'PATCH', route('cycle-dates-data.update', $id))->attribute('enctype', 'multipart/form-data')->id('cycle_date_validation_form')->open() }}
                        @else
                            {{ html()->form('POST', route('cycle-dates-data.store'))->attribute('enctype','multipart/form-data')->id('cycle_date_validation_form')->open() }}  
                        @endif
                        @csrf
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="d-block font-weight-bold">{{ __('message.slide_type') }}</label>
                                    <div class="custom-control custom-radio custom-control-inline col-2">
                                        {{ html()->radio('slide_type', old('slide_type', '0') == '0' || (isset($data) && $data->slide_type == '0'), '0')->id('slide_type_0')->class('custom-control-input') }}
                                        {{ html()->label(__('message.text_message'))->class('custom-control-label')->for('slide_type_0') }}
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline col-2">
                                        {{ html()->radio('slide_type', old('slide_type') == '1' || (isset($data) && $data->slide_type == '1'), '1')->id('slide_type_1')->class('custom-control-input')   }}
                                        {{ html()->label(__('message.question_answer'))->class('custom-control-label')->for('slide_type_1') }}
                                    </div>
                                </div>                                
                            </div>
                            
                            <div class="row">                                
                                    {!! html()->hidden('cycle_date_id', $cycle_date_id ?? $data->cycle_date_id)->id('cycle_dates') !!}
                                <div class="form-group col-md-4">
                                    {{ html()->label(__('message.title') . ' <span class="text-danger">*</span>')->class('form-control-label')->attribute('for', 'title') }}
                                    {{ html()->text('title', $data->title ?? old('title'))->class('form-control')->placeholder(__('message.title'))->required()  }}
                                </div>

                                <div class="form-group col-md-{{ isset($data) ? '3' : '4' }}" id="text_message_image_div">
                                    <label class="form-control-label" for="cycle_date_data_text_message_image">{{ __('message.image') }} </label>
                                    <div class="custom-file">
                                        <input type="file" name="cycle_date_data_text_message_image" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                    </div>
                                    <span class="selected_text_message_file"></span>
                                </div>

                                <div class="form-group col-md-4">
                                    {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>')->class('form-control-label')->attribute('for', 'status')   }}
                                    {{ html()->select('status', ['1' => __('message.active'), '0' => __('message.inactive')], old('status'))->class('form-control select2js')->required() }} 
                                </div>
                                

                                @if( isset($id) && getMediaFileExit($data, 'cycle_date_data_text_message_image'))
                                    <div class="col-md-1 mb-2">
                                        <img id="cycle_date_data_text_message_image_preview" src="{{ getSingleMedia($data,'cycle_date_data_text_message_image') }}" alt="health-experts-image" class="attachment-image mt-2">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'cycle_date_data_text_message_image']) }}"
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

                                <div class="form-group col-md-6" id="text_message_div">
                                    {{ html()->label(__('message.message'))->class('form-control-label')->for('message') }}
                                    {{ html()->textarea('message', $data->message ?? old('message'))->class('form-control tinymce-message')->placeholder(__('message.message'))->attribute('rows', 3)->attribute('cols', 40)  }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ html()->label(__('message.article'))->class('form-control-label')->for('article_id') }}
                                    {{ html()->select('article_id', isset($id) ? [ optional($data->article)->id => optional($data->article)->name ] : [])
                                        ->class('select2js form-group')
                                        ->id('article_id')
                                        ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_article']))
                                        ->attribute('data-allow-clear', 'true') 
                                    }}
                                </div>
                            </div>
                                
                            <div class="row d-none" id="que_ans_div">
                                @if (isset($questionAnswers) && count($questionAnswers) > 0)
                                    @foreach ($questionAnswers as $index => $qa)
                                        <div class="form-group col-md-3" id="text_message_image_div_{{ $index }}">
                                            <label class="form-control-label" for="cycle_date_data_que_ans_image_{{ $index + 1 }}">{{ __('message.image') . ' ' . ($index + 1) }} </label>
                                            <div class="custom-file">
                                                <input type="file" name="cycle_date_data_que_ans_image_{{ $index + 1 }}" class="custom-file-input" accept="image/*">
                                                <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                            </div>
                                            <span class="selected_que_ans_file_{{ $index + 1 }}"></span>
                                        </div>
                                        
                                        @if( isset($id) && getMediaFileExit($data, 'cycle_date_data_que_ans_image_' . ($index + 1)))
                                            <div class="col-md-1 mb-2">
                                                <img id="cycle_date_data_que_ans_image_{{ $index + 1 }}_preview" src="{{ getSingleMedia($data,'cycle_date_data_que_ans_image_' . ($index + 1)) }}" alt="health-experts-image" class="attachment-image mt-2">
                                                <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'cycle_date_data_que_ans_image_' . ($index + 1)]) }}"
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
                                
                                        <div class="form-group col-md-4">
                                            {{ html()->label(__('message.question') . ' ' . ($index + 1))->class('form-control-label')->for('question' . ($index + 1)) }}
                                            {{ html()->text('question[]', $qa['question'])->class('form-control')->placeholder(__('message.question')) }}
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            {{ html()->label(__('message.answer') . ' ' . ($index + 1))->class('form-control-label')->for('answer' . ($index + 1)) }}      
                                            {{ html()->text('answer[]', $qa['answer'])->class('form-control')->placeholder(__('message.answer')) }}
                                        </div>
                                    @endforeach
                                @else
                                    @for ($i = 1; $i <= 2; $i++)
                                        <div class="form-group col-md-4" id="text_message_image_div_{{ $i }}">
                                            <label class="form-control-label" for="cycle_date_data_que_ans_image_{{ $i }}">{{ __('message.image') . ' ' . $i }}</label>
                                            <div class="custom-file">
                                                <input type="file" name="cycle_date_data_que_ans_image_{{ $i }}" class="custom-file-input" accept="image/*">
                                                <label class="custom-file-label">{{ __('message.choose_file',['file' => __('message.image')]) }}</label>
                                            </div>
                                            <span class="selected_que_ans_file_{{ $i }}"></span>
                                        </div>
                                    
                                        @if(isset($id) && getMediaFileExit($data, 'cycle_date_data_que_ans_image_' . $i))
                                            <div class="col-md-1 mb-2">
                                                <img id="cycle_date_data_que_ans_image_{{ $i }}_preview" src="{{ getSingleMedia($data, 'cycle_date_data_que_ans_image_' . $i) }}" alt="health-experts-image" class="attachment-image mt-2">
                                                <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'cycle_date_data_que_ans_image_' . $i]) }}"
                                                    data--submit='confirm_form'
                                                    data--confirmation='true'
                                                    data--ajax='true'
                                                    data-toggle='tooltip'
                                                    title='{{ __("message.remove_file_title", ["name" => __("message.image")]) }}'
                                                    data-title='{{ __("message.remove_file_title", ["name" => __("message.image")]) }}'
                                                    data-message='{{ __("message.remove_file_msg") }}'>
                                                    <i class="ri-close-circle-line"></i>
                                                </a>
                                            </div>
                                        @endif
                                    
                                        <div class="form-group col-md-4">
                                            {{ html()->label(__('message.question') . ' ' . $i)->for('question' . $i)->class('form-control-label') }}
                                            {{ html()->text('question[]', isset($data['questions'][$i - 1]) ? $data['questions'][$i - 1] : old('question'))->placeholder(__('message.question'))->class('form-control') }}
                                        </div>
                                    
                                        <div class="form-group col-md-4">
                                            {{ html()->label(__('message.answer') . ' ' . $i)->for('answer' . $i)->class('form-control-label') }}
                                            {{ html()->text('answer[]', isset($data['answers'][$i - 1]) ? $data['answers'][$i - 1] : old('answer'))->placeholder(__('message.answer'))->class('form-control') }}
                                        </div>
                                    @endfor
                                @endif
                            </div>
                            <hr>
                            {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right') }}   
                        {{ html()->form()->close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @section('bottom_script')
        <script>
            $(document).ready(function(){
                formValidation("#cycle_date_validation_form", {
                    title: { required: true },
                }, {
                    title: { required: "Please enter a Title." },
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                function toggleDivVisibility(slideType) {
                    if (slideType === '0') {
                        $('#que_ans_div').addClass('d-none');
                        $('#text_message_div').removeClass('d-none');
                        $('#text_message_image_div').removeClass('d-none');
                    } else if (slideType === '1') {
                        $('#que_ans_div').removeClass('d-none');
                        $('#text_message_div').addClass('d-none');
                        $('#text_message_image_div').addClass('d-none');
                    }
                }

                var initialSlideType = $('input[type=radio][name=slide_type]:checked').val();
                toggleDivVisibility(initialSlideType);

                $('input[type=radio][name=slide_type]').change(function() {
                    toggleDivVisibility(this.value);
                });
            });
        </script>
    @endsection
</x-master-layout>
