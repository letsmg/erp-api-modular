<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (!app()->environment('testing'))
        @vite(['resources/js/app.ts'])
    @endif

    @auth
        @if(in_array(auth()->user()->access_level, [0, 1]))
            <meta name="robots" content="noindex, nofollow">
        @else
            <meta name="robots" content="index, follow">
        @endif
    @else
        <meta name="robots" content="index, follow">
    @endauth

    @if(isset($seo_global))
        @if($seo_global->google_tag_manager)
            {!! $seo_global->google_tag_manager !!}
        @endif

        @if($seo_global->schema_markup)
            <script type="application/ld+json">
                {!! $seo_global->schema_markup !!}
            </script>
        @endif
    @endif

    <title inertia>{{ config('app.name', 'ERP API Modular') }}</title>

    @routes
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>
</html>
