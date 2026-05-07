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
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            
            if(mainContent) mainContent.classList.add('page-loaded');

            const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])');
            
            links.forEach(link => {
                link.addEventListener('click', function (e) {
                    if (this.hostname === window.location.hostname && this.href !== window.location.href) {
                        e.preventDefault();
                        const destination = this.href;
                        if(mainContent) mainContent.classList.remove('page-loaded');
                        setTimeout(() => {
                            window.location.href = destination;
                        }, 150);
                    }
                });
            });
            
            window.addEventListener('pageshow', function (event) {
                if (event.persisted && mainContent) {
                    mainContent.classList.add('page-loaded');
                }
            });
        });

        // Popup de confirmação de exclusão
        let formToDelete = null;
        function confirmarExclusao(button) {
            formToDelete = button.closest('form');
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Esta ação não poderá ser desfeita!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                background: '#F9F7D3',
                color: '#5C3A2C',
                borderRadius: '1rem'
            }).then((result) => {
                if (result.isConfirmed && formToDelete) {
                    formToDelete.submit();
                }
            });
        }
    </script>
</body>
</html>
