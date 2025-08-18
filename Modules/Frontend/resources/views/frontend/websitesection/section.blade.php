{{ html()->form('POST', route('store.frontend.data', ['type' => $sub_type, 'frontend_id' => $frontend_id]))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}

    <div class="row">
        @foreach($data as $key => $value)
            @if( in_array($key, ['title'] ))
                <div class="col-md-12 form-group">
                    {{ html()->label(($sub_type === 'user-review' ? __('frontend::message.username') : __('frontend::message.'.$key)) . ' <span class="text-danger">*</span>', $key)->class('form-control-label') }}
                    {{ html()->text($key, $value ?? null)->placeholder($sub_type === 'user-review' ? __('frontend::message.username') : __('frontend::message.'.$key))->class('form-control') }}

                </div>
            @elseif (in_array($key,['subtitle']))
                @if (in_array($sub_type,['user-review']))
                    <div class="col-md-12 form-group">
                        {{ html()->label(($sub_type === 'user-review' ? __('frontend::message.country') : __('frontend::message.'.$key)) . ' <span class="text-danger">*</span>', $key)->class('form-control-label') }}
                        {{ html()->text($key, $value ?? null)->placeholder($sub_type === 'user-review' ? __('frontend::message.country') : __('frontend::message.'.$key))->class('form-control') }}
                    </div>
                @endif
            @elseif (in_array($key,['description']))
                <div class="col-md-12 form-group">
                    {{ html()->label(__('frontend::message.'.$key).' <span class="text-danger">*</span>', $key)->class('form-control-label') }}
                    {{ html()->textarea($key, $value ?? null)->class('form-control textarea')->rows(3)->placeholder(__('frontend::message.'.$key)) }}
                </div>
            @else
                @if (!in_array($sub_type,['user-review']))
                    <div class="form-group col-md-9">
                        <div class="custom-file">
                            <input type="file" name="{{ $key }}" class="form-control file-input custom-file-input" accept="image/*" data--target="{{$key}}_image_preview">
                            <label class="custom-file-label" for="{{ $key }}">{{ __('frontend::message.'.$key) }} </label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <img id="{{$sub_type}}_image_preview" src="{{ getSingleMedia($value, $sub_type) }}" alt="{{$sub_type}}" class="attachment-image mt-1 {{$key}}_image_preview">
                    </div>
                @endif
            @endif
        @endforeach
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-md btn-secondary" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-md btn-primary" id="btn_submit" data-form="ajax" >{{ __('message.save') }}</button>
    </div>
{{ html()->form()->close() }}
