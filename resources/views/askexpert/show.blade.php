<!-- Main Modal -->
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                @if(getMediaFileExit($data, 'askexpert_image'))
                    @php
                        $images = $data->getMedia('askexpert_image');
                        $file_extensions = ['png', 'jpg', 'jpeg', 'gif', 'avif'];
                    @endphp
                    <div class="row">
                        @foreach($images as $image)
                            @php
                                $extension = pathinfo($image->getFullUrl(), PATHINFO_EXTENSION);
                                $isValidExtension = $extension ? in_array(strtolower($extension), $file_extensions) : false;
                            @endphp

                            <div class="col-md-2 mb-4 text-center" id="image_preview_{{$image->id}}">
                                <div class="position-relative">
                                    <a href="javascript:void(0);"class="open-image-modal" data-image-url="{{ $image->getUrl() }}"title="{{ $image->name }}">
                                        @if($isValidExtension)
                                            <img src="{{ $image->getUrl() }}" alt="{{ $image->name }}" class="img-fluid rounded" style="height: 150px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/file.png') }}" class="img-fluid rounded" style="height: 150px; object-fit: cover;">
                                        @endif
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-md btn-secondary float-right mr-1" data-dismiss="modal">{{ __('message.close') }}</button>
        </div>
    </div>
</div>

<!-- Fullscreen Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body p-0">
        <img src="" id="imagePreviewModalImage" class="img-fluid w-100" style="max-height: 90vh; object-fit: contain;">
      </div>
    </div>
  </div>
</div>

<script>
    $(document).on('click', '.open-image-modal', function () {
        var imageUrl = $(this).data('image-url');
        $('#imagePreviewModalImage').attr('src', imageUrl);
        $('#imagePreviewModal').modal('show');
    });
</script>
