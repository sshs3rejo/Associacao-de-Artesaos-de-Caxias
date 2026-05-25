@extends('layouts.main')

@section('titulo', 'Activity Log')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Activity Log']]" />
    <x-alert type="success" :message="session('success')" />

    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="font-bold mb-1 text-2xl text-brand">Activity Log</h1>
            <p class="text-gray-500 mb-0">Registro de atividades do sistema.</p>
        </div>
        <x-back-button :route="route('admin.dashboard')" label="Voltar" />
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <form method="GET" class="flex gap-2 items-center">
                <select name="action" class="form-select rounded-lg border-gray-300 text-sm">
                    <option value="">Todas as ações</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ $action }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                @if(request('action'))
                    <a href="{{ route('admin.activity-log') }}" class="btn btn-sm btn-secondary">Limpar</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Ação</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Descrição</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Usuário</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                    @if(str_contains($log->action, 'aprovado')) bg-green-100 text-green-800
                                    @elseif(str_contains($log->action, 'rejeitado')) bg-red-100 text-red-800
                                    @elseif(str_contains($log->action, 'criado') || str_contains($log->action, 'realizada') || str_contains($log->action, 'proposto')) bg-blue-100 text-blue-800
                                    @elseif(str_contains($log->action, 'atualizado') || str_contains($log->action, 'atualizadas')) bg-yellow-100 text-yellow-800
                                    @elseif(str_contains($log->action, 'removido')) bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $log->description }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                @if($log->user)
                                    {{ $log->user->name }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum registro de atividade encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
