@extends('layouts.main')
@section('titulo', 'Gerenciar Artesãos')

@section('content')
<div class="container-fluid px-4 py-5">
    <h1 class="fw-bold mb-4" style="color: #7a2f1f;">Gerenciar Artesãos</h1>

    @if($artesos->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-people display-1 text-muted mb-3 d-block"></i>
            <p class="text-muted fs-5">Nenhum artesão cadastrado ainda.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white rounded-4 shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3">Nome</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Especialidade</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Perfil Público</th>
                        <th class="p-3">Cadastro</th>
                        <th class="p-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($artesos as $artesao)
                        <tr>
                            <td class="fw-semibold p-3">{{ $artesao->name }}</td>
                            <td class="p-3">{{ $artesao->email }}</td>
                            <td class="p-3">{{ $artesao->artisanProfile?->specialty ?? '-' }}</td>
                            <td class="p-3">
                                @if($artesao->artisanProfile?->isApproved())
                                    <span class="badge bg-success">Aprovado</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pendente</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($artesao->artisanProfile?->is_public)
                                    <span class="badge bg-info text-dark">Público</span>
                                @else
                                    <span class="badge bg-secondary">Privado</span>
                                @endif
                            </td>
                            <td class="p-3 small text-muted">{{ $artesao->created_at->format('d/m/Y') }}</td>
                            <td class="p-3">
                                <div class="d-flex gap-2">
                                    @if(!$artesao->artisanProfile?->isApproved())
                                        <form action="{{ route('admin.artesao.aprovar', $artesao) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Aprovar {{ $artesao->name }}?')">
                                                <i class="bi bi-check-lg"></i> Aprovar
                                            </button>
                                        </form>
                                    @endif
                                    @if($artesao->isActive())
                                        <form action="{{ route('admin.artesao.rejeitar', $artesao) }}" method="POST">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmarExclusao(this)">
                                                <i class="bi bi-x-lg"></i> Desativar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
