@extends('layouts.main')
@section('titulo', 'Usuários do Sistema')

@section('content')
<div class="container-fluid px-4 py-5">
    <h1 class="fw-bold mb-4" style="color: #7a2f1f;">Usuários do Sistema</h1>

    @if($usuarios->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-people display-1 text-muted mb-3 d-block"></i>
            <p class="text-muted fs-5">Nenhum usuário encontrado.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white rounded-4 shadow-sm overflow-hidden">
                <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                    <tr>
                        <th class="p-3">Nome</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Tipo</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Artesão</th>
                        <th class="p-3">Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $u)
                        <tr>
                            <td class="fw-semibold p-3">{{ $u->name }}</td>
                            <td class="p-3">{{ $u->email }}</td>
                            <td class="p-3">
                                @if($u->isAdmin())
                                    <span class="badge bg-dark">Admin</span>
                                @elseif($u->isArtisan())
                                    <span class="badge" style="background-color: #7a2f1f; color: #F9F7D3;">Artesão</span>
                                @else
                                    <span class="badge bg-secondary">Comprador</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($u->isActive())
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-danger">Inativo</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($u->artisanProfile)
                                    @if($u->artisanProfile->isApproved())
                                        <span class="badge bg-success">Aprovado</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="p-3 small text-muted">{{ $u->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $usuarios->links() }}
        </div>
    @endif
</div>
@endsection
