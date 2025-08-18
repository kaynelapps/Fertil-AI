{{ html()->form('POST', route('settingUpdate'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}
    {{ html()->hidden('adsconfig', 'adsconfig') }}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="card-title mb-0">{{ __('message.ads_config') }}</h4>           
            <div class="d-flex align-items-center">
                {{ html()->hidden('type[0]', 'adsconfig') }}
                {{ html()->hidden('key[0]', 'adsconfig_access') }}
                {{ html()->label(__('message.facebook_access'))->class('form-control-label mb-0 mr-2 text-nowrap')->for('facebook_access') }}
                {{ html()->select('value[0]', ['1' => __('message.yes'), '0' => __('message.no')], $settingsData['adsconfig_access'] ?? '1')
                    ->class('form-control select2js w-50')
                    ->id('facebook_access')
                    ->required() }}
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-3">{{__('message.android')}}</h4>
                    <div class="form-group">
                        {{ html()->hidden('type[1]', 'adsconfig') }}
                        {{ html()->hidden('key[1]', 'android_rewarded_video') }}
                        {{ html()->label(__('message.rewarded_video'))->class('form-control-label') }}
                        {{ html()->text('value[1]', $settingsData['android_rewarded_video'] ?? '')->class('form-control')->placeholder(__('message.rewarded_video')) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="mb-3">{{__('message.ios')}}</h4>

                    <div class="form-group">
                        {{ html()->hidden('type[2]', 'adsconfig') }}
                        {{ html()->hidden('key[2]', 'ios_rewarded_video') }}
                        {{ html()->label(__('message.rewarded_video'))->class('form-control-label') }}
                        {{ html()->text('value[2]', $settingsData['ios_rewarded_video'] ?? '')->class('form-control')->placeholder(__('message.rewarded_video')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('message.show_ads_when') }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            @php
                $adsFeatures = ['save_daily_logs', 'edit_period_data', 'view_paid_article_ads', 'download_image_data', 'download_pdf_data', 'download_doctor_report', 'use_calculator_tools'];
            @endphp

            @foreach($adsFeatures as $index => $key)
               <input type="hidden" name="key[{{ $index + 3 }}]" value="{{ $key }}">
                <input type="hidden" name="type[{{ $index + 3 }}]" value="adsaccess">

                <div class="form-group col-md-3">
                    <input type="hidden" name="value[{{ $index + 3 }}]" value="0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="adsCheckbox{{ $index + 3 }}" name="value[{{ $index + 3 }}]" value="1"
                            {{ old($key, $settingsDataAds[$key] ?? false) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="adsCheckbox{{ $index + 3 }}">
                            {{ __("message.{$key}") }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

    <div class="text-end mt-3">
        {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right') }}
    </div>
{{ html()->form()->close() }}
