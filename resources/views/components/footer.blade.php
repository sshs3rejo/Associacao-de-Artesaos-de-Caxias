<footer>
    <div class="container py-5">
        <div class="row g-4">
            <!-- Coluna 1: Logo e Sobre -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset(config('association.logo')) }}" alt="Logo" height="50" class="me-2">
                    <h3 class="h5 fw-bold mb-0" style="color: #F2EB85;">{{ config('association.name_short') }}</h3>
                </div>
                <p class="small opacity-75 pe-lg-5">
                    {{ config('association.description') }}
                </p>
            </div>

            <!-- Coluna 2: Links Rápidos -->
            <div class="col-lg-2 col-md-6">
                <h4 class="h6 fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Links</h4>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                    <li class="mb-2"><a href="{{ route('sobrenos') }}" class="footer-link">Sobre Nós</a></li>
                    <li class="mb-2"><a href="{{ route('produtos') }}" class="footer-link">Produtos</a></li>
                    <li class="mb-2"><a href="{{ route('evento') }}" class="footer-link">Eventos</a></li>
                    <li class="mb-2"><a href="{{ route('contato') }}" class="footer-link">Contato</a></li>
                </ul>
            </div>

            <!-- Coluna 3: Contato -->
            <div class="col-lg-3 col-md-6">
                <h4 class="h6 fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Contato</h4>
                <ul class="list-unstyled small opacity-75">
                    <li class="mb-2 d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill me-2 mt-1"></i>
                        <span>{{ config('association.address') }}</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-envelope-fill me-2"></i>
                        <span>{{ config('association.email') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Coluna 4: Redes Sociais -->
            <div class="col-lg-3 col-md-6 text-lg-end">
                <h4 class="h6 fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Siga-nos</h4>
                <div class="d-flex gap-3 justify-content-lg-end mb-4">
                    <a href="{{ config('association.facebook') }}" target="_blank" class="social-icon-btn" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="{{ config('association.instagram') }}" target="_blank" class="social-icon-btn" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>

        <hr class="my-4 opacity-10">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start small opacity-50">
                &copy; {{ date('Y') }} {{ config('association.name') }}. Todos os direitos reservados.
            </div>
            <div class="col-md-6 text-center text-md-end small mt-2 mt-md-0">
                <span class="opacity-50">{{ config('association.tagline') }}</span>
            </div>
        </div>
    </div>
</footer>
