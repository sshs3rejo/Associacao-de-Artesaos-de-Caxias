<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('titulo', config('association.name'))</title>

    <meta name="description" content="@yield('descricao', config('association.description'))">
    <meta property="og:title" content="@yield('titulo', config('association.name'))">
    <meta property="og:description" content="@yield('descricao', config('association.description'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset(config('association.logo')) }}">
    <meta name="twitter:card" content="summary">

    <link rel="icon" type="image/png" href="{{ asset(config('association.logo')) }}">
    <link rel="preload" href="{{ asset('fonts/outfit-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha384-nRgPTkuX86pH8yjPJUAFuASXQSSl2/bBUiNV47vSYpKFxHJhbcrGnmlYpYJMeD7a" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('style')
</head>
<body class="@yield('body_class')">

    <x-navbar />

    <main class="layout-main @yield('main_class')">
        @if($errors->any())
                    <div class="bg-red-100 text-red-800 px-4 py-3 rounded-lg border border-red-200 mb-4" role="alert" id="error-alert">
                        <div class="flex items-center gap-2 mb-1">
                            <x-icon name="exclamation" class="w-5 h-5" />
                            <span class="font-semibold">Erro</span>
                            <button onclick="this.closest('#error-alert').remove()" class="ml-auto text-red-600 hover:text-red-900 text-xl leading-none cursor-pointer border-0 bg-transparent">&times;</button>
                        </div>
                        <ul class="ml-6 list-disc text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif

        @yield('content')
    </main>

    <x-footer />

    <x-floating-whatsapp />

    @yield('modals')

    <div id="modal-confirm" class="modal-overlay">
        <div class="modal-content max-w-sm w-full mx-4 bg-white rounded-2xl shadow-2xl p-6 text-center">
            <x-icon name="alert-triangle" class="w-12 h-12 mx-auto text-amber-500 mb-3" />
            <h3 id="confirm-title" class="text-lg font-bold text-brand mb-2">Tem certeza?</h3>
            <p id="confirm-message" class="text-sm text-gray-600 mb-6">Esta ação não poderá ser desfeita!</p>
            <div class="flex gap-3 justify-center">
                <button onclick="hideModal('modal-confirm')" class="px-5 py-2 rounded-full border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer transition">Cancelar</button>
                <button id="confirm-btn-yes" class="px-5 py-2 rounded-full bg-red-600 text-white font-semibold hover:bg-red-700 cursor-pointer transition">Sim, excluir</button>
            </div>
        </div>
    </div>

    <div id="page-loader" class="page-loader"></div>

    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
</body>
</html>
