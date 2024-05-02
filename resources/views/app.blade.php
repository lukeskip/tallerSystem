<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
        @inertiaHead
        <script>
            window.app_url = "{{ url('/') }}"
        </script>
    </head>
    <body class="font-sans antialiased">

        @inertia
    </body>
</html>
