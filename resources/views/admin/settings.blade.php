@extends('layouts.main')

@section('titulo', 'Configurações do Sistema')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1" style="color: #7a2f1f;">Configurações do Sistema</h1>
            <p class="text-muted mb-0">Gerencie as informações públicas e chaves da Associação.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Voltar ao Painel
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Formulário Geral (2/3 de largura) -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header border-0 bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0" style="color: #7a2f1f;"><i class="fas fa-cog me-2"></i> Informações Gerais da Associação</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold small text-muted">Nome Completo da Associação</label>
                                <input type="text" name="name" id="name" class="form-control rounded-3" value="{{ old('name', config('association.name')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="name_short" class="form-label fw-bold small text-muted">Nome Curto (Exibição)</label>
                                <input type="text" name="name_short" id="name_short" class="form-control rounded-3" value="{{ old('name_short', config('association.name_short')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold small text-muted">E-mail de Contato</label>
                                <input type="email" name="email" id="email" class="form-control rounded-3" value="{{ old('email', config('association.email')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="whatsapp" class="form-label fw-bold small text-muted">Número do WhatsApp (DDD+Número)</label>
                                <input type="text" name="whatsapp" id="whatsapp" class="form-control rounded-3" value="{{ old('whatsapp', config('association.whatsapp')) }}" required>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label fw-bold small text-muted">Endereço</label>
                                <input type="text" name="address" id="address" class="form-control rounded-3" value="{{ old('address', config('association.address')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="instagram" class="form-label fw-bold small text-muted">Link do Instagram (Opcional)</label>
                                <input type="url" name="instagram" id="instagram" class="form-control rounded-3" value="{{ old('instagram', config('association.instagram')) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="facebook" class="form-label fw-bold small text-muted">Link do Facebook (Opcional)</label>
                                <input type="url" name="facebook" id="facebook" class="form-control rounded-3" value="{{ old('facebook', config('association.facebook')) }}">
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label fw-bold small text-muted">Sobre / Descrição da Associação</label>
                                <textarea name="description" id="description" class="form-control rounded-3" rows="4" required>{{ old('description', config('association.description')) }}</textarea>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                            <button type="submit" class="btn text-white fw-bold px-4 rounded-pill shadow-sm" style="background-color: #7a2f1f;">
                                <i class="fas fa-save me-1"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Atalhos Rápidos (1/3 de largura) -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3" style="color: #7a2f1f;"><i class="fas fa-users-cog me-2"></i> Usuários & Permissões</h5>
                    <p class="text-muted small">Gerencie o cadastro de administradores do sistema e visualize quem tem acesso à área protegida.</p>
                    <a href="{{ route('admin.usuarios') }}" class="btn btn-sm text-white w-100 rounded-pill fw-bold" style="background-color: #5C3A2C;">
                        <i class="fas fa-external-link-alt me-1"></i> Gerenciar Usuários
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3" style="color: #7a2f1f;"><i class="fas fa-database me-2"></i> Backup do Sistema</h5>
                    <p class="text-muted small">Realize o backup completo de todas as tabelas e arquivos armazenados localmente de forma simples.</p>
                    <button class="btn btn-sm btn-outline-secondary w-100 rounded-pill fw-bold" disabled>
                        <i class="fas fa-lock me-1"></i> Módulo Seguro (Em breve)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
