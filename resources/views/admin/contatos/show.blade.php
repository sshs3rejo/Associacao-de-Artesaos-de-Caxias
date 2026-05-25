@extends('layouts.main')

@section('titulo', 'Detalhes da Mensagem')

@section('content')

<div class="max-w-4xl mx-auto my-10 bg-white rounded-2xl shadow-lg px-8 py-10">
    <x-back-button :route="route('admin.contatos.index')" label="Voltar para lista" />

    <h1 class="text-2xl font-bold text-brand mb-6">Detalhes da Mensagem</h1>

    <x-alert message="{{ session('success') }}" />

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nome</label>
                <p class="text-gray-900">{{ $contato->nome }}</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Email</label>
                <p class="text-gray-900">{{ $contato->email }}</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Mensagem</label>
            <div class="bg-gray-50 rounded-lg p-4 text-gray-900 whitespace-pre-wrap">{{ $contato->mensagem }}</div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Status</label>
            <p class="text-gray-900">
                @if($contato->lido)
                    <span class="text-green-600"><x-icon name="check-circle" class="w-4 h-4" /> Lido</span>
                @else
                    <span class="text-yellow-600"><x-icon name="envelope" class="w-4 h-4" /> Novo</span>
                @endif
            </p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Recebido em</label>
            <p class="text-gray-900">{{ $contato->created_at->format('d/m/Y \à\s H:i') }}</p>
        </div>

        @if($contato->updated_at && $contato->updated_at != $contato->created_at)
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Atualizado em</label>
                <p class="text-gray-900">{{ $contato->updated_at->format('d/m/Y \à\s H:i') }}</p>
            </div>
        @endif

        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
            <x-back-button :route="route('admin.contatos.index')" label="Voltar para lista" />
            <form action="{{ route('admin.contatos.destroy', $contato->id) }}" method="POST" class="m-0">
                @csrf
                @method('DELETE')
                <button type="button" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition duration-200" onclick="var f=this.closest('form'); showConfirm('Tem certeza de que deseja excluir esta mensagem?',function(){f.submit();}); return false;">
                    <x-icon name="trash" class="w-4 h-4" /> Excluir Mensagem
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
