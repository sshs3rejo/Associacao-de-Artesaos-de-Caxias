<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('titulo', config('association.name'))</title>

    <link rel="icon" type="image/png" href="{{ asset(config('association.logo')) }}">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="preload" as="image" href="{{ asset('imagens/artesanato_alunos/back-logo.webp') }}" fetchpriority="high">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha384-t1nt8BQoYMLFN5p42tRAtuAAFQaCQODekUVeKKZrEnEyp4H2R0RHFz0KWpmj7i8g" crossorigin="anonymous">

    @yield('style')
</head>
<body class="@yield('body_class')">

    <x-navbar />

    <main class="layout-main @yield('main_class')">
        @if(session('success') || $errors->any())
            <div class="max-w-7xl mx-auto px-4 mt-3">
                <x-alert type="success" :message="session('success')" />
                @if($errors->any())
                    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                         class="bg-red-100 text-red-800 px-4 py-3 rounded-lg border border-red-200 mb-4" role="alert">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span class="font-semibold">Erro</span>
                            <button @click="show = false" class="ml-auto text-red-600 hover:text-red-900 text-xl leading-none">&times;</button>
                        </div>
                        <ul class="ml-6 list-disc text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <x-footer />

    <x-floating-whatsapp />

    @yield('modals')

    @yield('scripts')
</body>
</html>
