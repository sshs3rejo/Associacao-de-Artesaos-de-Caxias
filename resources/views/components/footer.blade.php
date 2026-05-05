<footer>
    <div class="container py-4">
        <div class="row g-4 text-center text-md-start align-items-center">
            <div class="col-md-4">
                <h3 class="h6 fw-bold mb-2" style="color: #F2EB85;">Associação dos Artesãos de Caxias</h3>
                <p class="small mb-0 opacity-75">Fortalecendo o artesanato local, cultura e tradição.</p>
            </div>
            <div class="col-md-4 text-md-center">
                <div class="small">
                    <a href="{{ route('sobrenos') }}" class="mx-2">Sobre</a>
                    <a href="{{ route('produtos') }}" class="mx-2">Produtos</a>
                    <a href="{{ route('contato') }}" class="mx-2">Contato</a>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex gap-3 justify-content-center justify-content-md-end">
                    <a href="https://www.facebook.com/p/Associação-dos-Artesãos-de-Caxias-100076232955626/?_rdr" target="_blank" class="fs-5" aria-label="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/artesaosdecaxias_ma" target="_blank" class="fs-5" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
                <div class="mt-2 x-small opacity-50" style="font-size: 0.7rem;">
                    &copy; {{ date('Y') }} Associação dos Artesãos de Caxias.
                </div>
            </div>
        </div>
    </div>
</footer>
