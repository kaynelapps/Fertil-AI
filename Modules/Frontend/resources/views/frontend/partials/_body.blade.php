@include('frontend::frontend.partials._body_header')

<div id="remoteModelData" class="modal fade" role="dialog"></div>
<div class="content-page">
    {{ $slot }}
</div>

@include('frontend::frontend.partials._body_footer')
@include('frontend::frontend.partials._scripts')
