<div id="loading">
    @include('partials._body_loader')
</div>
@include('partials._body_header')
<head>
    <style>
        :root {
            --site-color: {{ $themeColor }};
        }
    </style>
</head>

@include('partials._body_sidebar')

<div id="remoteModelData" class="modal fade" role="dialog"></div>
<div class="content-page">
    {{ $slot }}
</div>

@include('partials._body_footer')

@include('partials._scripts')
@include('partials._dynamic_script')
<div class="modal fade" id="formModal">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="formTitle">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="main_form"></div>
        </div>
        </div>
    </div>
</div>
    