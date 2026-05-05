<style>
    .navbar-logo {
        height: 30px;
    }
    .brand-text {
        font-size: 0.8rem;
        white-space: nowrap;
    }
    @media (min-width: 576px) {
        .navbar-logo { height: 40px; }
        .brand-text { font-size: 1.1rem; }
    }
    @media (min-width: 768px) {
        .navbar-logo { height: 45px; }
        .brand-text { font-size: 1.4rem; }
    }
    /* Estilo para o dropdown de admin */
    .admin-dropdown .dropdown-toggle::after {
        display: none;
    }
</style>
<header>
    <nav class="navbar navbar-expand-xl navbar-dark shadow-sm py-2" style="background-color: #7a2f1f; transition: all 0.3s ease;">
        <div class="container-fluid px-2 px-sm-4">
            <a class="navbar-brand d-flex align-items-center gap-1 gap-sm-2" href="{{ route('home') }}">
                <img src="{{ asset('imagens/artesanato_alunos/logo-artesaos.png') }}" alt="Logo" class="navbar-logo" style="transition: transform 0.3s ease;">
                <span class="fw-bold brand-text" style="color: #F9F7D3;">Associação dos Artesãos de Caxias</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2 gap-xl-4 align-items-xl-center">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" style="color: #F9F7D3; font-size: 1.1rem;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#F9F7D3'" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" style="color: #F9F7D3; font-size: 1.1rem;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#F9F7D3'" href="{{ route('sobrenos') }}">Sobre nós</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" style="color: #F9F7D3; font-size: 1.1rem;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#F9F7D3'" href="{{ route('evento') }}">Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" style="color: #F9F7D3; font-size: 1.1rem;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#F9F7D3'" href="{{ route('produtos') }}">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" style="color: #F9F7D3; font-size: 1.1rem;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#F9F7D3'" href="{{ route('contato') }}">Contato</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link fw-semibold px-4 py-2 rounded-pill border" style="color: #F9F7D3; font-size: 1.1rem; border-color: rgba(249, 247, 211, 0.3) !important;" onmouseover="this.style.backgroundColor='rgba(249, 247, 211, 0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#F9F7D3'" href="{{ route('login.form') }}">Login Admin</a>
                        </li>
                    @endguest
                    @auth
                        <li class="nav-item dropdown admin-dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-pill" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #7a2f1f; background-color: #F9F7D3;">
                                <i class="fas fa-user-shield"></i>
                                <span class="fw-bold d-xl-none">Menu Admin</span>
                                <i class="fas fa-cog"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" style="background-color: #F9F7D3;">
                                <li>
                                    <h6 class="dropdown-header fw-bold" style="color: #7a2f1f;">Olá, {{ auth()->user()->name }}</h6>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.dashboard') }}" style="color: #7a2f1f;">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.vendas.index') }}" style="color: #7a2f1f;">
                                        <i class="fas fa-history"></i> Histórico de Vendas
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.settings') }}" style="color: #7a2f1f;">
                                        <i class="fas fa-tools"></i> Configurações do Sistema
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger fw-bold">
                                            <i class="fas fa-sign-out-alt"></i> Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>
