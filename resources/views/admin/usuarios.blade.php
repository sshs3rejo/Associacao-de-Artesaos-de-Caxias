@extends('layouts.main')
@section('titulo', 'Usuários do Sistema')

@section('content')
<div class="container-fluid px-4 py-5">
    <h1 class="fw-bold mb-4" style="color: #7a2f1f;">Usuários do Sistema</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                        <th class="p-3">Função (Role)</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Artesão</th>
                        <th class="p-3">Cadastro</th>
                        <th class="p-3 text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $u)
                        <tr>
                            <td class="fw-semibold p-3">{{ $u->name }}</td>
                            <td class="p-3">{{ $u->email }}</td>
                            <td class="p-3">
                                <form action="{{ route('admin.usuarios.change-role', $u->id) }}" method="POST" class="d-flex align-items-center gap-1 m-0">
                                    @csrf
                                    <select name="role" class="form-select form-select-sm rounded-pill" style="max-width: 130px; font-size: 0.85rem;" onchange="this.form.submit()">
                                        <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>Comprador</option>
                                        <option value="artisan" {{ $u->role === 'artisan' ? 'selected' : '' }}>Artesão</option>
                                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
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
                            <td class="p-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Toggle Status Button --}}
                                    <form action="{{ route('admin.usuarios.toggle-status', $u->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @if($u->isActive())
                                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-3" title="Bloquear Usuário">
                                                <i class="bi bi-slash-circle"></i> Bloquear
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3" title="Reativar Usuário">
                                                <i class="bi bi-check-circle"></i> Ativar
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.usuarios.destroy', $u->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Tem certeza de que deseja excluir permanentemente o usuário {{ $u->name }} do sistema?')" title="Excluir Usuário">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
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
