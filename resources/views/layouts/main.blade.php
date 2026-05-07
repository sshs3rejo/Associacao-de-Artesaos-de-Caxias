<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('titulo', 'Associação dos Artesãos de Caxias')</title>
    
    <!-- Favicon (Logo na guia do navegador) -->
    <link rel="icon" type="image/png" href="{{ asset('imagens/artesanato_alunos/logo-artesaos.png') }}">
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
        <!-- Mensagens de Feedback -->
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <x-footer />
    
    <x-floating-whatsapp />

    <!-- Local para Modais (Evita bug de tela escura) -->
    @yield('modals')

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts específicos de cada página -->
    @yield('scripts')

    <!-- Transição de Página Suave (Fade In/Fade Out Apenas no Conteúdo) -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const mainContent = document.querySelector('.layout-main');
            
            // 1. A página terminou de carregar, adicionamos a classe para o Fade In
            if(mainContent) mainContent.classList.add('page-loaded');

            // 2. Interceptamos os cliques nos links
            const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])');
            
            links.forEach(link => {
                link.addEventListener('click', function (e) {
                    // Verifica se é um link do mesmo domínio e não é o link da página atual
                    if (this.hostname === window.location.hostname && this.href !== window.location.href) {
                        e.preventDefault(); // Impede o redirecionamento imediato
                        const destination = this.href;

                        // Remove a classe para iniciar o Fade Out
                        if(mainContent) mainContent.classList.remove('page-loaded');

                        // Aguarda 150ms (tempo da transição CSS) antes de redirecionar
                        setTimeout(() => {
                            window.location.href = destination;
                        }, 150);
                    }
                });
            });
            
            // 3. Restaurar estado ao voltar usando botão "Voltar" do navegador (BFCache)
            window.addEventListener('pageshow', function (event) {
                if (event.persisted && mainContent) {
                    mainContent.classList.add('page-loaded');
                }
            });
        });
    </script>
</body>
</html>
