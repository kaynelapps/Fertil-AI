<link rel="shortcut icon" class="site_favicon_preview" href="{{ secure_asset('images/favicon.ico') }}" />

<!-- Base CSS -->
<link rel="stylesheet" href="{{ secure_asset('vendor/bootstrap/css/bootstrap.min.css') }}"/>
<link rel="stylesheet" href="{{ secure_asset('css/backend-bundle.min.css') }}"/>
<link rel="stylesheet" href="{{ secure_asset('css/backend.css') }}"/>

<!-- RTL Support -->
@if(mighty_language_direction() == 'rtl')
    <link rel="stylesheet" href="{{ secure_asset('css/rtl.css') }}">
@endif

<!-- Vendor CSS -->
<link rel="stylesheet" href="{{ secure_asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}"/>
<link rel="stylesheet" href="{{ secure_asset('vendor/remixicon/fonts/remixicon.css') }}"/>
<link rel="stylesheet" href="{{ secure_asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ secure_asset('vendor/confirmJS/jquery-confirm.min.css') }}"/>
<link rel="stylesheet" href="{{ secure_asset('vendor/magnific-popup/css/magnific-popup.css') }}"/>
<link rel="stylesheet" href="{{ secure_asset('vendor/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ secure_asset('vendor/aos/aos.css') }}">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ secure_asset('css/custom.css') }}">

<!-- Phone Input CSS -->
@if(isset($assets) && in_array('phone', $assets))
    <link rel="stylesheet" href="{{ secure_asset('vendor/intlTelInput/css/intlTelInput.css') }}">
@endif
