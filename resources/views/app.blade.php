<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- 1. Lógica de Robots (Privacidade) --}}
        @auth
            @if(in_array(auth()->user()->access_level, [0, 1]))
                <meta name="robots" content="noindex, nofollow">
            @else
                <meta name="robots" content="index, follow">
            @endif
        @else
            <meta name="robots" content="index, follow">
        @endauth

        {{-- 2. Scripts Globais de SEO (GTM / ADS / Scripts) --}}
        @if(isset($seo_global))
            {{-- Google Tag Manager --}}
            @if($seo_global->google_tag_manager)
                {!! $seo_global->google_tag_manager !!}
            @endif

            {{-- Scripts de ADS --}}
            @if($seo_global->ads)
                {!! $seo_global->ads !!}
            @endif

            {{-- Schema Markup (JSON-LD geralmente) --}}
            @if($seo_global->schema_markup)
                <script type="application/ld+json">
                    {!! $seo_global->schema_markup !!}
                </script>
            @endif
        @endif

        {{-- 3. Título Padrão (Será sobrescrito pelo <Head> do Vue) --}}
        <title inertia>{{ config('app.name', 'ERP Vue Laravel') }}</title>

        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        
        {{-- 4. O InertiaHead injeta o que você colocar no <Head> do Vue aqui --}}
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia

        {{-- Scripts de rodapé se necessário --}}
    </body>
</html>