@extends('layouts.main')

@section('titulo', 'Configurações do Sistema')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold" style="color: #7a2f1f;">Configurações do Sistema</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">Voltar ao Painel</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-cog me-2"></i> Geral</h5>
                    <p class="text-muted small">Configurações básicas do site e informações da associação.</p>
                    <button class="btn btn-sm btn-secondary w-100" disabled title="Em desenvolvimento">Em breve</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-users-cog me-2"></i> Usuários</h5>
                    <p class="text-muted small">Gerenciar administradores e permissões de acesso.</p>
                    <a href="{{ route('admin.usuarios') }}" class="btn btn-sm btn-primary w-100">Gerenciar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-database me-2"></i> Backup</h5>
                    <p class="text-muted small">Realizar backup da base de dados e arquivos do sistema.</p>
                    <button class="btn btn-sm btn-secondary w-100" disabled title="Em desenvolvimento">Em breve</button>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-4">
        <i class="fas fa-info-circle me-2"></i> Algumas funções estão em desenvolvimento e serão liberadas em breve.
    </div>
</div>
@endsection
