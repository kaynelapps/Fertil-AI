<x-master-layout :assets="$assets ?? []">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">{{__('message.topic_data')}}</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('sectiondata.png') }}" alt="Section Data" class="img-fluid" style="max-width: 80%; height: auto;">
                    </div>
                    <h5 class="mb-4">{{__('message.step_to_add',['stepdata' => __('message.section_data')])}}</h5>

                    <ol class="list-unstyled">
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 1])}}</b></span> {{__('message.select_sections_list',['sections' => __('message.self_care')])}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 2])}}</b></span> {{__('message.add_form_title',[ 'form' => __('message.topic')])}}
                        </li>
                        <div class="text-center mb-4">
                            <img src="{{ asset('selfcare.png') }}" alt="Self Care" class="img-fluid" style="max-width: 60%; height: auto;">
                        </div>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 3])}}</b></span> {{__('message.save_update')}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 4])}}</b></span> {{__('message.topic_action')}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 5])}}</b></span> {{__('message.add_form_title',[ 'form' => __('message.self_care_data')])}}
                        </li>
                        <div class="text-center mb-4">
                            <img src="{{ asset('sectiondatamain.png') }}" alt="Self Care Data" class="img-fluid" style="max-width: 60%; height: auto;">
                        </div>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 6])}}</b></span> {{__('message.save_update')}}
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 7])}}</b></span> {{__('message.row_drag_and_drop')}}
                        </li>
                        <div class="text-center mb-4">
                            <img src="{{ asset('dragndroprowimage.png') }}" alt="Category Section" class="img-fluid" style="max-width: 60%; height: auto;">
                        </div>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>
