<header>
    <nav class="navbar navbar-expand-xl navbar-dark shadow-sm py-2" style="background-color: #7a2f1f; transition: all 0.3s ease;">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('imagens/artesanato_alunos/logo-artesaos.png') }}" alt="Logo" style="height: 45px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <span class="fw-bold fs-4" style="color: #F9F7D3;">Artesãos de Caxias</span>
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
                            <a class="nav-link fw-semibold px-4 py-2 rounded-pill border" style="color: #F9F7D3; font-size: 1.1rem; border-color: rgba(249, 247, 211, 0.3) !important;" onmouseover="this.style.backgroundColor='rgba(249, 247, 211, 0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#F9F7D3'" href="{{ route('login.form') }}">Login</a>
                        </li>
                    @endguest
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" style="color: #F9F7D3; font-size: 1.1rem;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#F9F7D3'" href="{{ route('admin.dashboard') }}">Painel Admin</a>
                            </li>
                        @endif
                        <li class="nav-item ms-xl-2">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn nav-link fw-bold px-4 py-2 rounded-pill" style="color: #7a2f1f; background-color: #F9F7D3; font-size: 1.1rem;" onmouseover="this.style.backgroundColor='#ffffff'" onmouseout="this.style.backgroundColor='#F9F7D3'">Sair</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>
