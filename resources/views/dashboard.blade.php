<x-app-layout>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-5">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle p-3" style="background-color: #F9F7D3;">
                    <i class="bi bi-person-check fs-2" style="color: #7a2f1f;"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold mb-1" style="color: #7a2f1f;">Bem-vindo, {{ Auth::user()->name }}!</h1>
                    <p class="text-muted mb-0">Você está logado com sucesso no sistema.</p>
                </div>
            </div>
            <p class="text-muted">Aqui é onde você pode começar a montar o conteúdo do seu site.</p>
        </div>
    </div>
</x-app-layout>
