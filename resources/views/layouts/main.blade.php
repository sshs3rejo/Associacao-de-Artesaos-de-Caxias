<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('titulo', 'Associação dos Artesãos de Caxias')</title>

    <!-- CSS principal -->
    <link rel="stylesheet" href="{{ asset('css/style-layout.css') }}">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Ícones do Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos adicionais por página -->
    @yield('style')
</head>
<body id="top" class="@yield('body_class')">

    <x-navbar />

    <!-- Conteúdo principal -->
    <main class="layout-main @yield('main_class')">
        @yield('content')
    </main>

    <x-footer />

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    

    
    <!-- Scripts específicos de cada página -->
    @yield('scripts')
</body>
</html>
