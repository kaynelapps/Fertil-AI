<x-master-layout :assets="$assets ?? []">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>  
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title"> {{__('message.based_on_symptoms')}} </h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">
                            <i class="fas fa-arrow-right"></i> <b>{{__('message.sympotms_list')}}</b>
                        </p>

                        <div class="text-center mb-4">
                            <img src="{{ asset('insights.png') }}" alt="Image Section" class="img-fluid" style="max-width: 80%; height: auto;">
                        </div>
                        <ol class="list-unstyled">
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 1])}}</b></span> {{__('message.add_form_title',[ 'form' => __('message.insights')])}}
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 2])}}</b></span> {{__('message.fill_data')}}
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 3])}}</b></span> {{__('message.save_update')}}
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title"> {{ __('message.based_on_cycleday')}} </h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">
                            <i class="fas fa-arrow-right"></i> <b>{{__('message.cycle_day_list')}}</b>
                        </p>

                        <div class="text-center mb-4">
                            <img src="{{ asset('cycledaydata.png') }}" alt="Image Section" class="img-fluid" style="max-width: 80%; height: auto;">
                        </div>
                        <ol class="list-unstyled">
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 1])}}</b></span> {{__('message.add_form_title',[ 'form' => __('message.cycle_dates')])}}
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 2])}}</b></span> {{__('message.fill_data')}}
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 3])}}</b></span> {{__('message.save_update')}}
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title"> {{ __('message.based_on_pregnancy')}} </h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">
                            <i class="fas fa-arrow-right"></i> <b>{{__('message.pregnancy_list')}}</b>
                        </p>

                        <div class="text-center mb-4">
                            <img src="{{ asset('pregnancyData.png') }}" alt="Image Section" class="img-fluid" style="max-width: 80%; height: auto;">
                        </div>
                        <ol class="list-unstyled">
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 1])}}</b></span> {{__('message.add_form_title',[ 'form' => __('message.pregnancy_date')])}}
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 2])}}</b></span> {{__('message.fill_data')}}
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 3])}}</b></span> {{__('message.save_update')}}
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title"> {{ __('message.personalinsights')}} </h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">
                            <i class="fas fa-arrow-right"></i> <b>{{__('message.personlation_list')}}</b>
                        </p>

                        <div class="text-center mb-4">
                            <img src="{{ asset('personalimage.png') }}" alt="Image Section" class="img-fluid" style="max-width: 80%; height: auto;">
                        </div>
                        <ol class="list-unstyled">
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 1])}}</b></span> {{__('message.add_form_title',[ 'form' => __('message.personalinsights')])}}
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 2])}}</b></span> {{__('message.fill_data')}}
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <span class="fw-bold me-3 mr-1"><b>{{__('message.step_name',['step' => 3])}}</b></span> {{__('message.save_update')}}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>
