<x-master-layout :assets="$assets ?? []">
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header1 d-flex justify-content-between mt-3 ml-3">
                        <div class="header-title">
                            <h4 class="card-title"><b>{{ $pageTitle }}</b>
                                <span class="" data-bs-toggle="tooltip" title="{{ __('message.help_info') }}">
                                    <i class="fas fa-info-circle fa-sm"></i>
                                </span>
                            </h4>
                        </div>
                        <div class="card-action mr-2 mb-2">
                            <a href="{{ route('category-help') }}" class="btn btn-sm loadRemoteModel mr-3 help" role="button">{{ __('message.help') }}</a>
                            <a href="{{ route('template.excel') }}" class="btn btn-md  mr-3 ml-3 btn-success" role="button">
                            <i class="fas fa-file-download mr-2"></i>{{ __('message.download') }}</a>
                        </div>
                    </div>
                    {{ html()->form('POST', route('import.categorydata'))->attribute('enctype', 'multipart/form-data')->id('uploadForm')->open() }}
                        <div class="card-body">
                            <div class="new-user-info">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="upload-area" id="uploadfile">
                                            <p>{{ __('message.drop_file') }}</p>
                                            {{ html()->file('file')->id('fileInput')->accept('.xlsx,.xls')->required()->attribute('hidden', true) }}
                                            <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click();">
                                                {{ __('Browse File') }}
                                            </button>
                                        </div>
                                        <div class="error-message text-danger mt-2" style="display: none;">
                                            {{ __('message.invalid_file_type') }}
                                        </div>
                                    </div>
                                </div>
                               {{ html()->button(__('message.import'))->type('submit')->class('btn btn-md btn-primary float-right mt-2 mb-2') }}
                            </div>
                        </div>
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
</x-master-layout>
