@extends('layouts.main')

@section('titulo', 'Meu Perfil - ' . config('association.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4" style="color: #7a2f1f;">
                        <i class="fas fa-user-circle me-2"></i>Meu Perfil
                    </h3>

                    <div class="mb-4">
                        <label class="fw-semibold text-muted small">Nome</label>
                        <p class="fs-5 mb-3">{{ $user->name }}</p>

                        <label class="fw-semibold text-muted small">Email</label>
                        <p class="fs-5 mb-3">{{ $user->email }}</p>
                    </div>

                    @if($user->isArtisan() && $profile && !$profile->isApproved())
                        <div class="alert alert-info">
                            <i class="bi bi-hourglass-split me-2"></i>
                            Sua solicitação para se tornar artesão está aguardando aprovação do administrador.
                            Você receberá uma notificação quando for aprovado.
                        </div>
                    @elseif(!$user->isArtisan())
                        <hr>
                        <h5 class="fw-bold mb-3" style="color: #7a2f1f;">
                            <i class="fas fa-hammer me-2"></i>Quero ser artesão
                        </h5>
                        <p class="text-muted mb-3">Preencha os dados abaixo para solicitar seu cadastro como artesão na associação.</p>

                        <form method="POST" action="{{ route('user.tornar-se-artesao') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="cpf" class="form-label fw-semibold">CPF <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00" maxlength="14" required>
                                    @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="telefone" class="form-label fw-semibold">Telefone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone') }}" placeholder="(11) 99999-9999" maxlength="20" required>
                                    @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="bio" class="form-label fw-semibold">Biografia / Sobre você</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3" placeholder="Conte um pouco sobre seu trabalho artesanal...">{{ old('bio') }}</textarea>
                                    @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="foto" class="form-label fw-semibold">Foto de Perfil</label>
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/jpeg,image/png">
                                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="form-text">Aceita JPG, PNG até 2MB.</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-lg rounded-pill px-5 fw-bold" style="background-color: #7a2f1f; color: #F9F7D3;">
                                    <i class="fas fa-paper-plane me-2"></i>Fazer Cadastro
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
