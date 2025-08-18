<x-frontend-layout :assets="$assets ?? []">
    
    <div class="py-5 site-sub-color">
        <div class="container-fluid">
            <div class="container">
                <div>
                    <ol class="breadcrumb mb-0">
                        <li><a class="text-decoration-none second-text" href="{{ route('browse') }}">{{ __('frontend::message.home') }}</a>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 4.16669L12.5 10L7.5 15.8334" stroke="#2C2C2C" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </li>
                        <li><a class="text-decoration-none second-text" href="">{{ __('frontend::message.calculators') }}</a>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 4.16669L12.5 10L7.5 15.8334" stroke="#2C2C2C" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </li>
                        <li class="second-text">{{ $data['calculator']->name }}</li>
                    </ol>
                    <h4 class="main-text line-height-46 fs-custom-32 font-weight-500 mt-3">{{ $data['calculator']->name }}</h4>

                    <span>
                        <div>{!! $data['calculator']->description !!}</div>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        @switch($data['calculator']->slug)
            @case('ovulation-calculator')
                @if (!$result)
                    <div class="calculate-box p-md-5 p-3 secondary-color d-flex flex-column justify-content-center radius-20">
                        <form id="ovulationForm" method="POST" action="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="periodDate" class="form-label main-text">{{ __('frontend::message.select_the_first_day_of_your_last_period') }}</label>
                                <input type="text" name="last_period" class="datepicker form-control period-date" id="periodDate" value="{{ old('last_period') }}">
                            </div>
                            <div class="mb-2 main-text">{{ __('frontend::message.how_long_is_your_average_cycle') }} ({{ __('frontend::message.in_days') }}</div>
                            <div class="counter-box w-100 d-flex justify-content-between align-items-center bg-white">
                                <button type="button" class="decrease main-text">−</button>
                                <div class="counter-value" id="cycleLength">28</div>
                                <input type="hidden" name="cycle_length" id="cycleLengthInput" value="28">
                                <button type="button" class="increase main-text">+</button>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn site-bg-color white-color calculate-btn btn-block mt-4">{{ __('frontend::message.calculate_fertility_days') }}</button>
                            </div>
                            <div class="note-box mt-5 p-3 bg-white radius-12">
                                <span class="main-body-text font-weight-500">{{ __('frontend::message.note') }} :</span>
                                <span class="main-body-text">
                                    {{ __('frontend::message.calculate_note') }}
                                </span>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($result)
                    <div class="calculate-box p-md-5 p-3 secondary-color d-flex flex-column justify-content-center radius-20">
                        <div class="mb-3 text-center">
                            <p class="main-text fs-custom-18">{{ __('frontend::message.your_best_days_to_conceive_are') }}</p>
                        </div>
                        <div class="row g-3 justify-content-center text-center mb-3">
                            <div class="col-12 col-md-6">
                                <div class="box-1 radius-10 h-100 d-flex flex-column justify-content-center cream-bg-color">
                                    <p class="display-5 fw-bold mb-0 white-color">{{ $result['fertileStart']->format('j') }}–{{ $result['fertileEnd']->format('j') }}</p>
                                    <p class="fs-3 mb-0 white-color">{{ $result['fertileStart']->format('F') }}</p>
                                </div>
                                <p class="mt-2 fs-custom-18 cream-color">{{ __('frontend::message.fertility_window') }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="box-2 radius-10 h-100 d-flex flex-column justify-content-center green-bg-color">
                                    <p class="display-5 fw-bold mb-0 white-color">{{ $result['ovulationDate']->format('j') }}</p>
                                    <p class="fs-3 mb-0 white-color">{{ $result['ovulationDate']->format('F') }}</p>
                                </div>
                                <p class="mt-2 fs-custom-18 green-color">{{ __('frontend::message.ovulation_day') }}</p>
                            </div>
                        </div>
            
                        <div class="d-grid mt-5">
                            <a href="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}" class="btn site-bg-color white-color calculate-btn btn-block mt-3">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.10414 5.60414L5.57381 5.07381V5.07381L6.10414 5.60414ZM4.83776 6.87052L4.08777 6.87429C4.08984 7.28556 4.42272 7.61844 4.83399 7.62051L4.83776 6.87052ZM7.37954 7.6333C7.79375 7.63539 8.13122 7.30129 8.1333 6.88708C8.13538 6.47287 7.80129 6.1354 7.38708 6.13332L7.37954 7.6333ZM5.57496 4.3212C5.57288 3.90699 5.23541 3.5729 4.8212 3.57498C4.40699 3.57706 4.0729 3.91453 4.07498 4.32874L5.57496 4.3212ZM4.32661 10.7849C4.38286 10.3745 4.09578 9.99627 3.6854 9.94002C3.27503 9.88377 2.89675 10.1708 2.8405 10.5812L4.32661 10.7849ZM18.8319 5.6681L19.3622 5.13777C15.542 1.31758 9.36873 1.27889 5.57381 5.07381L6.10414 5.60414L6.63447 6.13447C9.83358 2.93536 15.0571 2.95395 18.3016 6.19843L18.8319 5.6681ZM6.1681 18.3319L5.63777 18.8622C9.45796 22.6824 15.6313 22.7211 19.4262 18.9262L18.8959 18.3959L18.3655 17.8655C15.1664 21.0646 9.94291 21.0461 6.69843 17.8016L6.1681 18.3319ZM18.8959 18.3959L19.4262 18.9262C23.2211 15.1313 23.1824 8.95796 19.3622 5.13777L18.8319 5.6681L18.3016 6.19843C21.5461 9.44291 21.5646 14.6664 18.3655 17.8655L18.8959 18.3959ZM6.10414 5.60414L5.57381 5.07381L4.30743 6.34019L4.83776 6.87052L5.36809 7.40085L6.63447 6.13447L6.10414 5.60414ZM4.83776 6.87052L4.83399 7.62051L7.37954 7.6333L7.38331 6.88331L7.38708 6.13332L4.84153 6.12053L4.83776 6.87052ZM4.83776 6.87052L5.58775 6.86675L5.57496 4.3212L4.82497 4.32497L4.07498 4.32874L4.08777 6.87429L4.83776 6.87052ZM3.58355 10.6831L2.8405 10.5812C2.43907 13.5099 3.37392 16.5984 5.63777 18.8622L6.1681 18.3319L6.69843 17.8016C4.77785 15.881 3.98663 13.2652 4.32661 10.7849L3.58355 10.6831Z" fill="white"/>
                                </svg> 
                                {{ __('frontend::message.recalculate') }}
                            </a>
                        </div>
                    </div>
                @endif
            @break
            @case('pregnancy-test-calculator')
                @if (!$result)
                    <div class="calculate-box p-md-5 p-3 secondary-color d-flex flex-column justify-content-center radius-20">
                        <form method="POST" action="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="periodDate" class="form-label main-text">{{ __('frontend::message.select_the_first_day_of_your_last_period') }}</label>
                                <input type="date" name="last_period" class="form-control period-date datepicker" id="periodDate" value="{{ old('last_period') }}">
                            </div>
                            <div class="mb-2 main-text">{{ __('frontend::message.how_long_is_your_average_cycle') }} ({{ __('frontend::message.in_days') }})</div>
                            <div class="counter-box w-100 d-flex justify-content-between align-items-center bg-white">
                                <button type="button" class="decrease main-text">−</button>
                                <div class="counter-value" id="cycleLength">28</div>
                                <input type="hidden" name="cycle_length" id="cycleLengthInput" value="28">
                                <button type="button" class="increase main-text">+</button>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn site-bg-color white-color calculate-btn btn-block mt-4">{{ __('frontend::message.calculate_pregnancy_days') }}</button>
                            </div>
                            
                            <div class="note-box mt-5 p-3 bg-white radius-12">
                                <span class="main-body-text font-weight-500">{{ __('frontend::message.note') }} :</span>
                                <span class="main-body-text">
                                    {{ __('frontend::message.calculate_note') }}
                                </span>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($result)
                    <div class="calculate-box p-md-5 p-3 text-center secondary-color d-flex flex-column justify-content-center radius-20">
                        <p class="main-text mb-4 fs-custom-18">{{ __('frontend::message.first_day_you_can_test_is') }}</p>
                        <div class="box-1 radius-10 d-inline-block px-4 py-3 cream-bg-color">
                            <p class="display-5 mb-0 white-color">{{ $result['testDate']->format('j F, Y') }}</p>
                        </div>
                        <div class="d-grid mt-5">
                            <a href="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}" class="btn site-bg-color white-color calculate-btn btn-block mt-3">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.10414 5.60414L5.57381 5.07381V5.07381L6.10414 5.60414ZM4.83776 6.87052L4.08777 6.87429C4.08984 7.28556 4.42272 7.61844 4.83399 7.62051L4.83776 6.87052ZM7.37954 7.6333C7.79375 7.63539 8.13122 7.30129 8.1333 6.88708C8.13538 6.47287 7.80129 6.1354 7.38708 6.13332L7.37954 7.6333ZM5.57496 4.3212C5.57288 3.90699 5.23541 3.5729 4.8212 3.57498C4.40699 3.57706 4.0729 3.91453 4.07498 4.32874L5.57496 4.3212ZM4.32661 10.7849C4.38286 10.3745 4.09578 9.99627 3.6854 9.94002C3.27503 9.88377 2.89675 10.1708 2.8405 10.5812L4.32661 10.7849ZM18.8319 5.6681L19.3622 5.13777C15.542 1.31758 9.36873 1.27889 5.57381 5.07381L6.10414 5.60414L6.63447 6.13447C9.83358 2.93536 15.0571 2.95395 18.3016 6.19843L18.8319 5.6681ZM6.1681 18.3319L5.63777 18.8622C9.45796 22.6824 15.6313 22.7211 19.4262 18.9262L18.8959 18.3959L18.3655 17.8655C15.1664 21.0646 9.94291 21.0461 6.69843 17.8016L6.1681 18.3319ZM18.8959 18.3959L19.4262 18.9262C23.2211 15.1313 23.1824 8.95796 19.3622 5.13777L18.8319 5.6681L18.3016 6.19843C21.5461 9.44291 21.5646 14.6664 18.3655 17.8655L18.8959 18.3959ZM6.10414 5.60414L5.57381 5.07381L4.30743 6.34019L4.83776 6.87052L5.36809 7.40085L6.63447 6.13447L6.10414 5.60414ZM4.83776 6.87052L4.83399 7.62051L7.37954 7.6333L7.38331 6.88331L7.38708 6.13332L4.84153 6.12053L4.83776 6.87052ZM4.83776 6.87052L5.58775 6.86675L5.57496 4.3212L4.82497 4.32497L4.07498 4.32874L4.08777 6.87429L4.83776 6.87052ZM3.58355 10.6831L2.8405 10.5812C2.43907 13.5099 3.37392 16.5984 5.63777 18.8622L6.1681 18.3319L6.69843 17.8016C4.77785 15.881 3.98663 13.2652 4.32661 10.7849L3.58355 10.6831Z" fill="white"/>
                                </svg> 
                                {{ __('frontend::message.recalculate') }}
                            </a>
                        </div>
                    </div>
                @endif
            @break
            @case('period-calculator')
                @if (!$result)
                    <div class="period-calculate-box p-md-5 p-3 secondary-color d-flex flex-column justify-content-center radius-20">
                        <form method="POST" action="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="periodDate" class="form-label main-text">{{ __('frontend::message.select_the_first_day_of_your_last_period') }}</label>
                                <input type="date" name="last_period" class="datepicker form-control period-date" id="periodDate" value="{{ old('last_period') }}">
                            </div>
                            <div class="mb-2 main-text">{{ __('frontend::message.how_long_is_your_average_cycle') }} ({{ __('frontend::message.in_days') }})</div>
                            <div class="counter-box w-100 d-flex justify-content-between align-items-center bg-white mb-4">
                                <button type="button" class="decrease main-text">−</button>
                                <div class="counter-value" id="cycleLength">28</div>
                                <input type="hidden" name="cycle_length" id="cycleLengthInput" value="28">
                                <button type="button" class="increase main-text">+</button>
                            </div>
                            <div class="mb-2 main-text">{{ __('frontend::message.how_many_days_did_it_last') }}</div>
                            <div class="counter-box w-100 d-flex justify-content-between align-items-center bg-white mb-3">
                                <button type="button" class="decrease-duration main-text">−</button>
                                <div class="counter-value" id="periodDuration">5</div>
                                <input type="hidden" name="period_duration" id="periodDurationInput" value="5">
                                <button type="button" class="increase-duration main-text">+</button>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn site-bg-color white-color calculate-btn btn-block mt-4">{{ __('frontend::message.calculate_fertility_days') }}</button>
                            </div>
                            <div class="note-box mt-5 p-3 bg-white radius-12">
                                <span class="main-body-text font-weight-500">{{ __('frontend::message.note') }} :</span>
                                <span class="main-body-text">
                                    {{ __('frontend::message.calculate_note') }}
                                </span>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($result)
                    <div class="period-calculate-box p-md-5 p-3 text-center secondary-color d-flex flex-column justify-content-center radius-20">
                        <p class="main-text mb-4 fs-custom-18">{{ __('frontend::message.your_estimated_period_dates_are') }}</p>
                        <div class="box-1 radius-10 h-50 d-flex flex-column justify-content-center red-bg-color">
                            <p class="display-5 font-weight-500 line-height-46 mb-0 white-color">
                                {{ $result['next_period_start']->format('j') }}–{{ $result['next_period_end']->format('j') }}
                            </p>
                            @php
                                $startMonth = $result['next_period_start']->format('M');
                                $endMonth = $result['next_period_end']->format('M');
                            @endphp

                            <p class="fs-3 mb-0 white-color">
                                {{ $startMonth !== $endMonth ? ($startMonth . '–' . $endMonth) : $startMonth }}
                            </p>
                        </div>
                        <div class="d-grid mt-5">
                            <a href="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}" class="btn site-bg-color white-color mt-4">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.10414 5.60414L5.57381 5.07381V5.07381L6.10414 5.60414ZM4.83776 6.87052L4.08777 6.87429C4.08984 7.28556 4.42272 7.61844 4.83399 7.62051L4.83776 6.87052ZM7.37954 7.6333C7.79375 7.63539 8.13122 7.30129 8.1333 6.88708C8.13538 6.47287 7.80129 6.1354 7.38708 6.13332L7.37954 7.6333ZM5.57496 4.3212C5.57288 3.90699 5.23541 3.5729 4.8212 3.57498C4.40699 3.57706 4.0729 3.91453 4.07498 4.32874L5.57496 4.3212ZM4.32661 10.7849C4.38286 10.3745 4.09578 9.99627 3.6854 9.94002C3.27503 9.88377 2.89675 10.1708 2.8405 10.5812L4.32661 10.7849ZM18.8319 5.6681L19.3622 5.13777C15.542 1.31758 9.36873 1.27889 5.57381 5.07381L6.10414 5.60414L6.63447 6.13447C9.83358 2.93536 15.0571 2.95395 18.3016 6.19843L18.8319 5.6681ZM6.1681 18.3319L5.63777 18.8622C9.45796 22.6824 15.6313 22.7211 19.4262 18.9262L18.8959 18.3959L18.3655 17.8655C15.1664 21.0646 9.94291 21.0461 6.69843 17.8016L6.1681 18.3319ZM18.8959 18.3959L19.4262 18.9262C23.2211 15.1313 23.1824 8.95796 19.3622 5.13777L18.8319 5.6681L18.3016 6.19843C21.5461 9.44291 21.5646 14.6664 18.3655 17.8655L18.8959 18.3959ZM6.10414 5.60414L5.57381 5.07381L4.30743 6.34019L4.83776 6.87052L5.36809 7.40085L6.63447 6.13447L6.10414 5.60414ZM4.83776 6.87052L4.83399 7.62051L7.37954 7.6333L7.38331 6.88331L7.38708 6.13332L4.84153 6.12053L4.83776 6.87052ZM4.83776 6.87052L5.58775 6.86675L5.57496 4.3212L4.82497 4.32497L4.07498 4.32874L4.08777 6.87429L4.83776 6.87052ZM3.58355 10.6831L2.8405 10.5812C2.43907 13.5099 3.37392 16.5984 5.63777 18.8622L6.1681 18.3319L6.69843 17.8016C4.77785 15.881 3.98663 13.2652 4.32661 10.7849L3.58355 10.6831Z" fill="white"/>
                                </svg> 
                                {{ __('frontend::message.recalculate') }}
                            </a>
                        </div>
                    </div>
                @endif
            @break

            @case('implantation-calculator')
                @if (!$result)
                    <div class="calculate-box p-md-5 p-3 secondary-color d-flex flex-column justify-content-center radius-20">
                        <form method="POST" action="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="last_period" class="form-label main-text">{{ __('frontend::message.select_the_first_day_of_your_last_period') }}</label>
                                <input type="text" name="last_period" id="periodDate" class="period-date datepicker form-control" value="{{ old('last_period') }}">
                            </div>
                            <div class="counter-box w-100 d-flex justify-content-between align-items-center bg-white mb-4">
                                <button type="button" class="implantation-decrease main-text">−</button>
                                <div class="counter-value" id="implantationCalculator">28</div>
                                <input type="hidden" name="cycle_length" id="implantationCalculatorInput" value="28">
                                <button type="button" class="implantation-increase main-text">+</button>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn site-bg-color white-color calculate-btn btn-block mt-4">
                                    {{ __('frontend::message.calculate_implantation') }}
                                </button>
                            </div>
                            <div class="note-box mt-5 p-3 bg-white radius-12">
                                <span class="main-body-text font-weight-500">{{ __('frontend::message.note') }} :</span>
                                <span class="main-body-text">
                                    {{ __('frontend::message.calculate_note') }}
                                </span>
                            </div>
                        </form>
                    </div>
                @endif
        
                @if ($result)
                    <div class="calculate-box p-md-5 p-3 text-center secondary-color d-flex flex-column justify-content-center radius-20">
                        <p class="main-text mb-3 fs-custom-18 mb-4">{{ __('frontend::message.your_implantation_range_is_between') }}</p>
            
                        <div class="box-1 radius-10 p-4 green-bg-color">
                            <p class="display-5 fw-bold white-color mb-0 font-weight-500 line-height-72">
                                {{ $result['implant_start']->format('j') }}–{{ $result['implant_end']->format('j') }}
                            </p>
            
                            @php
                                $startMonth = $result['implant_start']->format('M');
                                $endMonth = $result['implant_end']->format('M');
                            @endphp
            
                            <p class="fs-3 mb-0 white-color font-weight-500 line-height-46">
                                {{ $startMonth !== $endMonth ? ($startMonth . '-' . $endMonth) : $startMonth }}
                            </p>
                        </div>
                        <a href="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}" class="btn site-bg-color white-color mt-5">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.10414 5.60414L5.57381 5.07381V5.07381L6.10414 5.60414ZM4.83776 6.87052L4.08777 6.87429C4.08984 7.28556 4.42272 7.61844 4.83399 7.62051L4.83776 6.87052ZM7.37954 7.6333C7.79375 7.63539 8.13122 7.30129 8.1333 6.88708C8.13538 6.47287 7.80129 6.1354 7.38708 6.13332L7.37954 7.6333ZM5.57496 4.3212C5.57288 3.90699 5.23541 3.5729 4.8212 3.57498C4.40699 3.57706 4.0729 3.91453 4.07498 4.32874L5.57496 4.3212ZM4.32661 10.7849C4.38286 10.3745 4.09578 9.99627 3.6854 9.94002C3.27503 9.88377 2.89675 10.1708 2.8405 10.5812L4.32661 10.7849ZM18.8319 5.6681L19.3622 5.13777C15.542 1.31758 9.36873 1.27889 5.57381 5.07381L6.10414 5.60414L6.63447 6.13447C9.83358 2.93536 15.0571 2.95395 18.3016 6.19843L18.8319 5.6681ZM6.1681 18.3319L5.63777 18.8622C9.45796 22.6824 15.6313 22.7211 19.4262 18.9262L18.8959 18.3959L18.3655 17.8655C15.1664 21.0646 9.94291 21.0461 6.69843 17.8016L6.1681 18.3319ZM18.8959 18.3959L19.4262 18.9262C23.2211 15.1313 23.1824 8.95796 19.3622 5.13777L18.8319 5.6681L18.3016 6.19843C21.5461 9.44291 21.5646 14.6664 18.3655 17.8655L18.8959 18.3959ZM6.10414 5.60414L5.57381 5.07381L4.30743 6.34019L4.83776 6.87052L5.36809 7.40085L6.63447 6.13447L6.10414 5.60414ZM4.83776 6.87052L4.83399 7.62051L7.37954 7.6333L7.38331 6.88331L7.38708 6.13332L4.84153 6.12053L4.83776 6.87052ZM4.83776 6.87052L5.58775 6.86675L5.57496 4.3212L4.82497 4.32497L4.07498 4.32874L4.08777 6.87429L4.83776 6.87052ZM3.58355 10.6831L2.8405 10.5812C2.43907 13.5099 3.37392 16.5984 5.63777 18.8622L6.1681 18.3319L6.69843 17.8016C4.77785 15.881 3.98663 13.2652 4.32661 10.7849L3.58355 10.6831Z" fill="white"/>
                            </svg> 
                            {{ __('frontend::message.recalculate') }}
                        </a>
                    </div>
                @endif
            @break

            @case('pregnancy-due-date-calculator')
                @if (!$result)
                    <div class="pregnancy-due-date-box p-md-5 p-3 secondary-color d-flex flex-column justify-content-center radius-20">
                        <form method="POST" action="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}">
                            @csrf

                            <div class="mb-3">
                                <label for="last_period" class="form-label main-text">{{ __('frontend::message.select_the_first_day_of_your_last_period') }}</label>
                                <input type="text" name="last_period" class="datepicker form-control period-date" id="periodDate" value="{{ old('last_period') }}">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn site-bg-color white-color calculate-btn btn-block mt-4">
                                    {{ __('frontend::message.calculate_due_date') }}
                                </button>
                            </div>
                            <div class="note-box mt-5 p-3 bg-white radius-12">
                                <span class="main-body-text font-weight-500">{{ __('frontend::message.note') }} :</span>
                                <span class="main-body-text">
                                    {{ __('frontend::message.calculate_note') }}
                                </span>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($result)
                    <div class="pregnancy-due-date-box p-md-5 p-3 text-center secondary-color d-flex flex-column justify-content-center radius-20">
                        <p class="main-text mb-4 fs-custom-18">{{ __('frontend::message.you_will_meet_your_baby_on') }}</p>
                        <div class="box-1 radius-10 p-4 green-bg-color">
                            <p class="display-5 font-weight-500 line-height-46 white-color mb-0">
                                {{ $result['due_date']->format('F j Y') }}
                            </p>
                            <p class="display-6 font-weight-500 line-height-46 white-color mb-0">
                                {{ $result['due_date']->format('l') }}
                            </p>
                        </div>
                        <a href="{{ route('calculator', ['slug' => $data['calculator']->slug]) }}" class="btn site-bg-color white-color mt-4">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.10414 5.60414L5.57381 5.07381V5.07381L6.10414 5.60414ZM4.83776 6.87052L4.08777 6.87429C4.08984 7.28556 4.42272 7.61844 4.83399 7.62051L4.83776 6.87052ZM7.37954 7.6333C7.79375 7.63539 8.13122 7.30129 8.1333 6.88708C8.13538 6.47287 7.80129 6.1354 7.38708 6.13332L7.37954 7.6333ZM5.57496 4.3212C5.57288 3.90699 5.23541 3.5729 4.8212 3.57498C4.40699 3.57706 4.0729 3.91453 4.07498 4.32874L5.57496 4.3212ZM4.32661 10.7849C4.38286 10.3745 4.09578 9.99627 3.6854 9.94002C3.27503 9.88377 2.89675 10.1708 2.8405 10.5812L4.32661 10.7849ZM18.8319 5.6681L19.3622 5.13777C15.542 1.31758 9.36873 1.27889 5.57381 5.07381L6.10414 5.60414L6.63447 6.13447C9.83358 2.93536 15.0571 2.95395 18.3016 6.19843L18.8319 5.6681ZM6.1681 18.3319L5.63777 18.8622C9.45796 22.6824 15.6313 22.7211 19.4262 18.9262L18.8959 18.3959L18.3655 17.8655C15.1664 21.0646 9.94291 21.0461 6.69843 17.8016L6.1681 18.3319ZM18.8959 18.3959L19.4262 18.9262C23.2211 15.1313 23.1824 8.95796 19.3622 5.13777L18.8319 5.6681L18.3016 6.19843C21.5461 9.44291 21.5646 14.6664 18.3655 17.8655L18.8959 18.3959ZM6.10414 5.60414L5.57381 5.07381L4.30743 6.34019L4.83776 6.87052L5.36809 7.40085L6.63447 6.13447L6.10414 5.60414ZM4.83776 6.87052L4.83399 7.62051L7.37954 7.6333L7.38331 6.88331L7.38708 6.13332L4.84153 6.12053L4.83776 6.87052ZM4.83776 6.87052L5.58775 6.86675L5.57496 4.3212L4.82497 4.32497L4.07498 4.32874L4.08777 6.87429L4.83776 6.87052ZM3.58355 10.6831L2.8405 10.5812C2.43907 13.5099 3.37392 16.5984 5.63777 18.8622L6.1681 18.3319L6.69843 17.8016C4.77785 15.881 3.98663 13.2652 4.32661 10.7849L3.58355 10.6831Z" fill="white"/>
                            </svg> 
                            {{ __('frontend::message.recalculate') }}
                        </a>
                    </div>
                @endif
            @break
            @default
        @endswitch

        <div class="row">
            <h4 class="fs-custom-32 font-weight-500 line-height-46 second-text mb-4">{{ optional($data['calculator'])->article->name }}</h4>
            <div class="col-md-12">
                @php
                    $article = optional($data['calculator']->article);
                @endphp
                <img src="{{ getSingleMediaSettingImage($article->id != null ? $article : null, 'article_image', 'article_image') }}" alt="Article Img" class="img-fluid article-cal-image radius-20 w-100 mb-3">
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <span class="heading-sub">{{ __('frontend::message.published') }} </span>
                    <span class="site-base-color me-2">{{ date('d F Y', strtotime(optional($data['calculator'])->created_at)) }}</span> |
                    <span class="heading-sub ms-2">{{ __('frontend::message.written_by') }} </span> 
                    <span class="site-base-color">{{ optional($data['calculator']->article->health_experts->users)->display_name }}</span>
                </div>
                <div class="mb-3">
                    <span class="heading-sub me-2">{{ __('message.tags') }}:</span>
                    <div class="d-inline-flex flex-wrap gap-2">
                        @foreach($selected_tags as $tag)
                            <a href="{{ route('articles.by.tag', $tag->slug) }}" class="px-3 py-1 radius-8 secondary-color site-color text-decoration-none">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div>{!! $data['calculator']->article->description !!}</div>
            </div>
        </div>
    </div>

    @section('bottom_script')
        <script>
            $(document).ready(function () {

                const today = new Date().toISOString().split('T')[0];
                $('#periodDate').val(today);

                let min = 21;
                let max = 35;
                let minumun = 21;
                let maximum = 44;
                let value = parseInt($('#cycleLength').text());
                let implantationValue = parseInt($('#implantationCalculator').text());

                $('.increase').click(function (e) {
                    e.preventDefault();
                    if (value < max) {
                        value++;
                        $('#cycleLength').text(value);
                        $('#cycleLengthInput').val(value);
                        $('#implantationCalculator').text(value);
                        $('#implantationCalculatorInput').val(value);
                    }
                });

                $('.decrease').click(function (e) {
                    e.preventDefault();
                    if (value > min) {
                        value--;
                        $('#cycleLength').text(value);
                        $('#cycleLengthInput').val(value);
                    }
                });

                $('.implantation-increase').click(function (e) {
                    e.preventDefault();
                    if (implantationValue < maximum) {
                        implantationValue++;
                        $('#implantationCalculator').text(implantationValue);
                        $('#implantationCalculatorInput').val(implantationValue);
                    }
                });

                $('.implantation-decrease').click(function (e) {
                    e.preventDefault();
                    if (implantationValue > min) {
                        implantationValue--;
                        $('#implantationCalculator').text(implantationValue);
                        $('#implantationCalculator').val(implantationValue);
                    }
                });

                let durationValue = 5;
                $('.increase-duration').click(function () {
                    if (durationValue < 7) {
                        durationValue++;
                        $('#periodDuration').text(durationValue);
                        $('#periodDurationInput').val(durationValue);
                    }
                });
                $('.decrease-duration').click(function () {
                    if (durationValue > 2) {
                        durationValue--;
                        $('#periodDuration').text(durationValue);
                        $('#periodDurationInput').val(durationValue);
                    }
                });
            });
        </script>
    @endsection

</x-frontend-layout>
