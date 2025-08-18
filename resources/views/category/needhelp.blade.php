<x-master-layout :assets="$assets ?? []">
    <div class="row">
        <div class="text-center col-lg-12">
            <img src="{{ asset('helpimage.png') }}" alt="Image Section" class="img-fluid">
        </div>
        <div class="col-lg-12">
            <div class="card mt-1">
                <div class="card-header">
                    <h4 class="card-title">{{__('message.image_section')}}</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('imagesections.png') }}" alt="Image Section" class="img-fluid" style="max-width: 80%; height: auto;">
                    </div>

                    <h5 class="mb-4">{{__('message.step_to_add',['stepdata' => __('message.image_section')])}}</h5>

                    <ol class="list-unstyled">
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 1])}}</b></span> {{__('message.select_sections_list',['sections' => __('message.image_section')])}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 2])}}</b></span> {{__('message.add_form_title',[ 'form' => __('message.image_section')])}}
                        </li>
                        <div class="text-center mb-4">
                            <img src="{{ asset('cetogroyimage.png') }}" alt="Category Section" class="img-fluid" style="max-width: 60%; height: auto;">
                        </div>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 3])}}</b></span> {{__('message.choose_category')}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 4])}}</b></span> {{__('message.upload_image')}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 5])}}</b></span> {{__('message.save_update')}}
                        </li>
                    </ol>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">{{__('message.info_section')}}</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('infosection.png') }}" alt="Info Section" class="img-fluid" style="max-width: 80%; height: auto;">
                    </div>
                    <h5 class="mb-4">{{__('message.step_to_add',['stepdata' => __('message.info_section')])}}</h5>

                    <ol class="list-unstyled">
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 1])}}</b></span>  {{__('message.select_sections_list',['sections' => __('message.info_section')])}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 2])}}</b></span> {{__('message.add_form_title',[ 'form' => __('message.sections')])}}
                        </li>
                        <div class="text-center mb-4">
                            <img src="{{ asset('listandaddinfo.png') }}" alt="List and Add Info" class="img-fluid" style="max-width: 60%; height: auto;">
                        </div>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 3])}}</b></span> {{__('message.fill_data')}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 4])}}</b></span> {{__('message.save_update')}}
                        </li>
                    </ol>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">{{__('message.common_que_ans')}}</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('qutionanswer.png') }}" alt="Q&A Section" class="img-fluid" style="max-width: 80%; height: auto;">
                    </div>
                    <h5 class="mb-4">{{__('message.step_to_add',['stepdata' => __('message.common_que_ans')])}}</h5>

                    <ol class="list-unstyled">
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 1])}}</b></span> {{__('message.select_sections_list',['sections' => __('message.common_que_ans')])}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 2])}}</b></span>{{__('message.add_form_title',[ 'form' => __('message.common_que_ans')])}}
                        </li>
                        <div class="text-center mb-4">
                            <img src="{{ asset('commonqueanswers.png') }}" alt="Common Q&A" class="img-fluid" style="max-width: 60%; height: auto;">
                        </div>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 3])}}</b></span> {{__('message.set_q&a')}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 4])}}</b></span> {{__('message.save_update')}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>
