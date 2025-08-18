{{ html()->form('POST', route('settingUpdate'))->attribute('data-toggle', 'validator')->open() }}

    {{ html()->hidden('id', null, ['class' => 'form-control'] ) }}
    {{ html()->hidden('page', $page, ['class' => 'form-control'] ) }}
    {{ html()->hidden('appversion', 'appversion') }}
    <div class="col-md-12 mt-20">
        <div class="row">
            @php
                $config = config('mobile-config');
                $appVersions = config('mobile-config.APPVERSION');
            @endphp
                        
            @foreach($config as $key => $value)
                @foreach($value as $sub_keys => $sub_value)
                @if($key == 'APPVERSION')
                 @php
                    $data=null;
                    foreach($setting_value as $v){

                        if($v->key==($key.'_'.$sub_keys)){
                            $data = $v;
                        }
                    }
                @endphp
                {{ html()->hidden('type[]', $key)->class('form-control') }}
                <input type="hidden" name="key[]" value="{{ $key.'_'.$sub_keys }}">
                
                @if($key == 'APPVERSION' && $sub_keys == 'ANDROID_FORCE_UPDATE')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="{{ $key.'_'.$sub_keys }}">{{ str_replace('_',' ',$sub_keys) }} </label> 
                        {{ html()->select('value[]', ['1' => __('message.yes'), '0' => __('message.no')], isset($data) ? $data->value : '1')->class('form-control select2js') }}
                    </div>
                </div>
                 
                @elseif($key == 'APPVERSION' && $sub_keys == 'IOS_FORCE_UPDATE')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="{{ $key.'_'.$sub_keys }}">{{ str_replace('_',' ',$sub_keys) }} </label> 
                       {{ html()->select('value[]', ['1' => __('message.yes'), '0' => __('message.no')], isset($data) ? $data->value : '1')->class('form-control select2js') }}
                    </div>
                </div>


                @elseif($key == 'APPVERSION' && $sub_keys == 'ANDROID_VERSION_CODE')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="{{ $key.'_'.$sub_keys }}">{{ str_replace('_',' ',$sub_keys) }}</label>
                        <input type="number" name="value[]" value="{{ isset($data) ? $data->value : null }}" id="{{ $key.'_'.$sub_keys }}" class="form-control form-control-lg app-version-input" placeholder="{{ str_replace('_',' ',$sub_keys) }}"min="0"oninput="this.value = this.value.replace(/[^0-9]/g,'');">
                    </div>
                </div>
                @elseif($key == 'APPVERSION' && $sub_keys == 'PLAYSTORE_URL')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="{{ $key.'_'.$sub_keys }}">{{ str_replace('_',' ',$sub_keys) }} </label> 
                        <input type="text" name="value[]"value="{{ isset($data) ? $data->value : null }}"id="{{ $key.'_'.$sub_keys }}"class="form-control form-control-lg app-version-input"placeholder="{{ str_replace('_',' ',$sub_keys) }}">
                    </div>
                </div>
                @elseif($key == 'APPVERSION' && $sub_keys == 'IOS_VERSION')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="{{ $key.'_'.$sub_keys }}">{{ str_replace('_',' ',$sub_keys) }} </label> 
                        <input type="number" name="value[]"value="{{ isset($data) ? $data->value : null }}"id="{{ $key.'_'.$sub_keys }}"class="form-control form-control-lg app-version-input"placeholder="{{ str_replace('_',' ',$sub_keys) }}" min="0"oninput="this.value = this.value.replace(/[^0-9]/g,'');">
                    </div>
                </div>
                @elseif($key == 'APPVERSION' && $sub_keys == 'APPSTORE_URL')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="{{ $key.'_'.$sub_keys }}">{{ str_replace('_',' ',$sub_keys) }} </label> 
                        <input type="text" name="value[]"value="{{ isset($data) ? $data->value : null }}"id="{{ $key.'_'.$sub_keys }}"class="form-control form-control-lg app-version-input"placeholder="{{ str_replace('_',' ',$sub_keys) }}">
                    </div>
                </div>
                @endif
                @endif
                @endforeach
           
                {{-- @if($key == 'APPVERSION')
                <div class="form-group col-md-4">
                    <label class="d-block">{{ __('message.force_update') }} </label>

                    {{ html()->hidden('type[]', 'APPVERSION') }}
                    <input type="hidden" name="key[]" value="APPVERSION_FORCE_UPDATE">

                    <div class="custom-control custom-radio custom-control-inline col-2">
                        {!! html()->radio('value[]', isset($forceUpdate) && $forceUpdate->value == '1', '1')
                            ->class('custom-control-input')
                            ->id('force_update_yes_'.$key) !!}
                        {!! html()->label(__('message.yes'))
                            ->for('force_update_yes_'.$key)
                            ->class('custom-control-label') !!}
                    </div>

                    <div class="custom-control custom-radio custom-control-inline col-2">
                        {{ html()->radio('value[]', isset($forceUpdate) && $forceUpdate->value == '0', '0')->class('custom-control-input')->id('force_update_no_'.$key) }}
                        {{ html()->label(__('message.no'))->for('force_update_no_'.$key)->class('custom-control-label') }}
                    </div>
                </div>
                @endif --}}
            @endforeach
        </div>
    </div>
    {{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-md-right') }}
    {{ html()->form()->close() }}