<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @foreach([
            '/assets/admin/plugins/global/plugins.bundle.css',
            '/assets/admin/css/style.bundle.css',
        ] as $asset)
            <link rel="stylesheet" href="{{ asset($asset) }}">
        @endforeach

        <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.8.1/nouislider.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    </head>


    <body id="kt_body" class="app-blank">

        {{ $slot }}

	</body>


</html>
