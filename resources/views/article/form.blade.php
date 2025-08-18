<x-app-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {{ html()->modelForm($data, 'PATCH', route('article.update', $id))->attribute('enctype', 'multipart/form-data')->id('article_validation_form')->open() }}
        @else
            {{ html()->form('POST', route('article.store'))->attribute('enctype','multipart/form-data')->id('article_validation_form')->open() }}
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('article.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                                {{ html()->text('name', old('name'))->placeholder(__('message.name'))->class('form-control')->required() }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.tags') . ' <span class="text-danger">*</span>', 'tags_id')->class('form-control-label') }}
                                {{ html()->select('tags_id[]', $selected_tags ?? [], $data->tags_id ?? old('tags_id'))->class('select2js form-group tags')->multiple()->attribute('data-placeholder', __('message.select_name', ['select' => __('message.tags')]))->attribute('data-ajax--url', route('ajax-list', ['type' => 'tags'])) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.goal_type') . ' <span class="text-danger">*</span>', 'goal_type')->class('form-control-label') }}
                                {{ html()->select('goal_type', ['' => ''] + getGoalType(), $data->goal_type ?? old('goal_type'))->class('select2js form-group type')->id('goal_type_id')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.goal_type')]))->attribute('data-allow-clear', 'true') }}
                            </div>
                            <div class="form-group col-md-6" id="article_container">
                                {{ html()->label(__('message.article_use_for') . ' <span class="text-danger">*</span>', 'article_type')->class('form-control-label') }}
                                {{ html()->select('article_type', ['' => ''] + getArticleType(), $data->article_type ?? ('article_type'))->class('select2js form-group type')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.article_use_for')]))->attribute('data-allow-clear', 'true') }}
                            </div>

                            <div class="form-group col-md-6" id="sub_symptoms_container" style="display: none;">
                                {{ html()->label(__('message.sub_symptoms') . ' <span class="text-danger">*</span>', 'sub_symptoms_id')->class('form-control-label') }}
                                {{ html()->select('sub_symptoms_id[]', $selected_subsymptoms, $selectedIds ?? [])
                                    ->class('select2js form-group')
                                    ->id('symptoms_id')
                                    ->multiple()
                                    ->attribute('data-placeholder', __('message.select_name', ['select' => __('message.sub_symptoms')]))
                                    ->attribute('data-ajax--url', route('ajax-list', ['type' => 'sub_symptoms_category']))
                                    ->attribute('data-allow-clear', 'true') }}
                            </div>

                            <div class="form-group col-md-6" id="week_container" style="display: none;">
                                {{ html()->label(__('message.week'))->class('form-control-label') }}

                                {{ html()->select('weeks', isset($id) ? [ $data->weeks => $data->weeks . ' ' . __('message.week')] : [], old('week'))->class('select2js form-group')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.week')]))->attribute('data-ajax--url', route('ajax-list', ['type' => 'pregnancy_week']))->attribute('data-allow-clear', 'true') }}

                            </div>
                           
                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.health_experts') . ' <span class="text-danger">*</span>', 'expert_id')->class('form-control-label') }}

                                {{ html()->select('expert_id', isset($id) ? [ $data->health_experts->id => $data->health_experts->users->display_name ] : [], old('expert_id'))->class('select2js form-group')->attribute('data-placeholder', __('message.select_name', ['select' => __('message.health_experts')]))->attribute('data-ajax--url', route('ajax-list', ['type' => 'get_health_expert']))->attribute('data-allow-clear', 'true') }}

                            </div>
                          
                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}

                                {{ html()->select('status', ['1' => __('message.active'),'0' => __('message.inactive')], old('status'))->class('form-control select2js') }}
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-control-label" for="image">{{ __('message.image') }} </label>
                                <div class="custom-file">
                                    <input type="file" name="article_image" class="custom-file-input" accept="image/*">
                                    <label class="custom-file-label">{{  __('message.choose_file',['file' =>  __('message.image') ]) }}</label>
                                </div>
                                <span class="selected_file"></span>
                            </div>
                            @if( isset($id) && getMediaFileExit($data, 'article_image'))
                                <div class="col-md-2 mb-2">
                                    <img id="article_image_preview" src="{{ getSingleMedia($data,'article_image') }}" alt="article-image" class="attachment-image mt-1">
                                    <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $data->id, 'type' => 'article_image']) }}"
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
                                {{ html()->label(__('message.type') . ' <span class="text-danger">*</span>', 'type')->class('form-control-label') }}
                                {{ html()->select('type', ['free' => __('message.free'),'paid' => __('message.paid')], old('type'))->class('form-control select2js') }}
                            </div>
                            {{-- @if(Module::has('Frontend') && Module::isEnabled('Frontend')) 
                                <div class="col-md-3 ml-3 mb-3 custom-control custom-checkbox custom-inline">
                                    <input type="checkbox" name="send_mail_to_subscribers" value="1" class="custom-control-input" id="send_mail_to_subscribers">
                                    <label class="custom-control-label" for="send_mail_to_subscribers">{{ __('frontend::message.send_mail_to_subscribers') }}<label>
                                </div>
                            @endif        --}}
                            <div class="form-group col-md-12">
                                {{ html()->label(__('message.description'), 'description')->class('form-control-label') }}
                                {{ html()->textarea('description', null)->class('form-control tinymce-description')->placeholder(__('message.description'))->attributes(['rows' => 3, 'cols' => 40]) }}
                            </div>

                            <div class=" form-group col-md-12 mt-3">
                                <h5>{{ __('message.reference') }}<button type="button" id="add_button" class="btn mb-3 btn-sm btn-primary float-right">{{ __('message.add_form_title',['form' => '']) }}</button></h5>
                                    @if(isset($id) && count($data->article_reference) > 0)
                                    <table id="table_list" class="table border-none">
                                        <tbody>
                                            @foreach( $data->article_reference as $index => $value)
                                                <tr id="row_{{ $index }}" row="{{ $index }}" data-id="{{ $value->id }}">
                                                    {{ html()->hidden('article_reference_id[' . $index . ']', $value->id ?? null)->id('article_reference_id_no_' . $index)->class('form-control') }}
                                                    <td></td>
                                                    <td class="col-md-11">
                                                        <div class="form-group mt-3">
                                                            {{ html()->text('reference[' . $index . ']', $value->reference)->id('reference_no_' . $index)->placeholder(__('message.reference'))->class('form-control') }}
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <div class="form-group mt-3">
                                                            <a href="javascript:void(0)" id="remove_{{$index}}" class="removebtn btn btn-sm btn-icon btn-danger" row="{{$index}}">
                                                                <i class="ri-delete-bin-2-fill"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <table id="table_list" class="table border-none">
                                        <tbody>
                                            <tr id="row_0" row="0" data-id="0">
                                                {{ html()->hidden('article_reference_id[]', null)->id('article_reference_id_no_0')->class('form-control') }}
                                                <td></td>
                                                <td class="col-md-11">
                                                    <div class="form-group mt-3">
                                                        {{ html()->text('reference[]', is_array(old('reference')) ? old('reference')[0] ?? null : null)->id('reference_no_0')->placeholder(__('message.reference'))->class('form-control') }}
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group mt-3">
                                                        <a href="javascript:void(0)" id="remove_0" class="removebtn btn btn-sm btn-icon btn-danger" row="0">
                                                            <i class="ri-delete-bin-2-fill"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @endif
                                <hr>
                            </div>
                        </div>
                        <hr>
                        {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right') }}
                    </div>
                </div>
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
    @section('bottom_script')
        <script>
            $(document).ready(function(){
                formValidation("#article_validation_form", {
                    name: { required: true },
                    "tags_id[]": { required: true },
                    goal_type: { required: true },
                    article_type: { required: true },
                    expert_id: { required: true },
                    trimester: { required: true },
                }, {
                    name: { required: "Please enter a Name." },
                    "tags_id[]" : { required: "Please select a Tags."},
                    goal_type: { required: "Please select a goal type."},
                    article_type: { required: "Please select a Article use for."},
                    expert_id: { required: "Please select a Health Experts."},
                    trimester: { required: "Please select a Trimester."}
                });
            });
        </script>
        <script>
            (function ($) {
                $(document).ready(function () {
                    var $insightsType = $('select[name="article_type"]');
                    $insightsType.on('change', function () {
                        var selectedValue = $(this).val();
                        if (selectedValue == '0') {
                            $('#sub_symptoms_container').show();
                        } else {
                            $('#sub_symptoms_container').hide();
                        }
                    });

                    $insightsType.trigger('change');
                   var $insightsType = $('select[name="article_type"]');
                    function toggleFields() {
                        var goalType = $('#goal_type_id').val();

                        if (goalType == "1") {
                            $('#week_container').show();
                            $('#article_container').hide();
                        } else {
                        $('#week_container').hide();
                            $('#article_container').show();
                        }
                    }
                    $('#goal_type_id').change(function () {
                        toggleFields();
                    });
                    toggleFields();
                    function updateInsightsType() {
                        var goalType = $('#goal_type_id').val();
                        var $insightsType = $('select[name="article_type"]');

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

                    tinymceEditor('.tinymce-description',' ',function (ed) { }, 450)

                    var resetSequenceNumbers = function() {
                        $("#table_list tbody tr").each(function(i) {
                            $(this).find('td:first').text(i + 1);
                        });
                    };

                    resetSequenceNumbers();

                    $(".clone_select2js").select2();
                    var row = 0;
                    $('#add_button').on('click', function ()
                    {
                        $(".select2js").select2("destroy");
                        var tableBody = $('#table_list').find("tbody");
                        var trLast = tableBody.find("tr:last");

                        trLast.find(".removebtn").show().fadeIn(300);

                        var trNew = trLast.clone();
                        row = trNew.attr('row');
                        row++;

                        trNew.attr('id','row_'+row).attr('data-id',0).attr('row',row);
                        trNew.find('[type="hidden"]').val(0).attr('data-id',0);
                        trNew.find('[id^="article_reference_id_no_"]').attr('name',"article_reference_id["+row+"]").attr('id',"article_reference_id_no_"+row).val('');
                        trNew.find('[id^="reference_no_"]').attr('name',"reference["+row+"]").attr('id',"reference_no_"+row).val('');
                        trNew.find('[id^="remove_"]').attr('id',"remove_"+row).attr('row',row);

                        trLast.after(trNew);
                        $(".select2js").select2();
                        resetSequenceNumbers();
                    });

                    $(document).on('click','.removebtn', function()
                    {
                        var row = $(this).attr('row');
                        var delete_row  = $('#row_'+row);
                        var check_exists_id = delete_row.attr('data-id');
                        var total_row = $('#table_list tbody tr').length;
                        var user_response = confirm("Are you sure?");
                        if(!user_response) {
                            return false;
                        }

                        if(total_row == 1){
                            $(document).find('#add_button').trigger('click');
                        }
                        if(check_exists_id != 0 ) {
                            $.ajax({
                            url: "{{ route('article.reference.delete')}}",
                            type: 'post',
                            data: {'id': check_exists_id, '_token': $('input[name=_token]').val()},
                            dataType: 'json',
                            success: function (response) {
                                if(response['status']) {
                                    delete_row.remove();
                                    showMessage(response.message);
                                } else {
                                    errorMessage(response.message);
                                }
                            }
                        });
                        } else {
                            delete_row.remove();
                        }
                        resetSequenceNumbers();
                    })
                });
            })(jQuery);
        </script>
    @endsection
</x-app-layout>
