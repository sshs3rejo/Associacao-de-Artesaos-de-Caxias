@extends('layouts.main')
@section('titulo', 'Meu Perfil - Artesão')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4" style="color: #7a2f1f;">Meu Perfil de Artesão</h1>

            @if(!$perfil->isApproved())
                <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-info-circle-fill"></i>
                    Seu perfil está aguardando aprovação do administrador.
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('artesan.perfil.atualizar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nome</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Telefone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $perfil->phone) }}" placeholder="(99) 99999-9999">
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Especialidade</label>
                                <input type="text" name="specialty" class="form-control" value="{{ old('specialty', $perfil->specialty) }}" placeholder="Ex: Cerâmica, Bordado, Palha...">
                                @error('specialty') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $perfil->whatsapp) }}" placeholder="(99) 99999-9999">
                                @error('whatsapp') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Instagram</label>
                                <input type="text" name="instagram" class="form-control" value="{{ old('instagram', $perfil->instagram) }}" placeholder="@usuario">
                                @error('instagram') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Facebook</label>
                                <input type="text" name="facebook" class="form-control" value="{{ old('facebook', $perfil->facebook) }}" placeholder="facebook.com/usuario">
                                @error('facebook') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Bio</label>
                                <textarea name="bio" class="form-control" rows="4" placeholder="Conte um pouco sobre seu trabalho...">{{ old('bio', $perfil->bio) }}</textarea>
                                @error('bio') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Foto de Perfil</label>
                                <input type="file" name="profile_photo" class="form-control" accept="image/*">
                                @error('profile_photo') <small class="text-danger">{{ $message }}</small> @enderror
                                @if($perfil->profile_photo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $perfil->profile_photo) }}" alt="Foto" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_public" value="0">
                                    <input type="checkbox" name="is_public" value="1" class="form-check-input" role="switch" id="isPublic" {{ $perfil->is_public ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="isPublic">Perfil público no site</label>
                                    <small class="d-block text-muted">Seu perfil aparecerá na página de artesãos</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn fw-bold px-5" style="background-color: #7a2f1f; color: #F9F7D3;">
                                <i class="bi bi-check-lg me-2"></i>Salvar Perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
