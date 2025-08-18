{{ html()->form('POST', route('settingsUpdates'))->open() }}
{!! html()->hidden('id', isset($setting_value[0]) ? $setting_value[0]['id'] : null) !!}
{{ html()->hidden('page', $page)->placeholder('id')->class('form-control') }}
<div class="card shadow mb-10">
    <div class="card-body">
        <div class="row"> 
            <div class="col-md-12">
                 @php
                    $data = null;
                    foreach($setting_value as $v){
                        
                    }
                @endphp
                <div class="row">
                    <div class="form-group col-md-4">
                        {{ html()->label(__('message.database_backup') . ' <span class="text-danger">*</span>')->class('form-control-label')  }}
                        {{ html()->select('backup_type', ['daily' => __('message.daily'),'monthly' => __('message.monthly'),'weekly' => __('message.weekly'),'none' => __('message.none')], isset($v) ? $v->backup_type : 'daily')->class('form-control select2js') }}
                    </div>
                    @if(auth()->user()->hasRole('super_admin'))
                        <div class="form-group col-md-4">
                            {{ html()->label(__('message.email') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                            {{ html()->email('backup_email', isset($v) ? optional($v)->backup_email : old('backup_email'))->placeholder(__('message.email'))->class('form-control')->attribute('required', true)->attribute('readonly', true) }}
                        </div>
                    @else
                        <div class="form-group col-md-4">
                            {{ html()->label(__('message.email') . ' <span class="text-danger">*</span>')->class('form-control-label') }}
                            {{ html()->email('backup_email', isset($v) ? optional($v)->backup_email : old('backup_email'))->placeholder(__('message.email'))->class('form-control')->attribute('required', true) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{ html()->submit(__('message.save'))->class('btn btn-md btn-primary float-right') }}   
{{ html()->form()->close() }}
<script>
        $(document).ready(function() {

        $('.select2js').select2();
        });
</script>




