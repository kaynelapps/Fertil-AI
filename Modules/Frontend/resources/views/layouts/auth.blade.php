<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ mighty_language_direction() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('frontend::frontend.partials._head')
</head>
<body id="app">
    <div class="wrapper">
        {{ $slot }}
    </div>

    @include('frontend::frontend.partials._scripts')

</body>
</html>
