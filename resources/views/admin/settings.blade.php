@extends('layouts.main')

@section('titulo', 'Configurações do Sistema')

@section('content')
<div class="w-full px-4 py-5">
    <x-breadcrumb :items="[['Home', route('home')], ['Painel', route('admin.dashboard')], ['Configurações']]" />
    <x-alert type="success" :message="session('success')" />
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="font-bold mb-1 text-2xl text-brand">Configurações do Sistema</h1>
            <p class="text-gray-500 mb-0">Gerencie as informações públicas e chaves da Associação.</p>
        </div>
        <x-back-button :route="route('admin.dashboard')" label="Voltar" />
    </div>

    <div class="grid grid-cols-12 gap-4">
        <!-- Formulário Geral (2/3 de largura) -->
        <div class="lg:col-span-8 col-span-12">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-white py-3 border-b px-4">
                    <h5 class="font-bold mb-0" style="color: #7a2f1f;"><x-icon name="cog" class="w-4 h-4 me-2" /> Informações Gerais da Associação</h5>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-12 gap-3">
                            <div class="md:col-span-6 col-span-12">
                                <x-input name="name" label="Nome Completo da Associação" type="text" value="{{ old('name', config('association.name')) }}" required />
                            </div>
                            <div class="md:col-span-6 col-span-12">
                                <x-input name="name_short" label="Nome Curto (Exibição)" type="text" value="{{ old('name_short', config('association.name_short')) }}" required />
                            </div>
                            <div class="md:col-span-6 col-span-12">
                                <x-input name="email" label="E-mail de Contato" type="email" value="{{ old('email', config('association.email')) }}" required />
                            </div>
                            <div class="md:col-span-6 col-span-12">
                                <x-input name="whatsapp" label="Número do WhatsApp (DDD+Número)" type="text" value="{{ old('whatsapp', config('association.whatsapp')) }}" required />
                            </div>
                            <div class="col-span-12">
                                <x-input name="address" label="Endereço" type="text" value="{{ old('address', config('association.address')) }}" required />
                            </div>
                            <div class="md:col-span-6 col-span-12">
                                <x-input name="latitude" label="Latitude" type="text" value="{{ old('latitude', config('association.latitude')) }}" />
                            </div>
                            <div class="md:col-span-6 col-span-12">
                                <x-input name="longitude" label="Longitude" type="text" value="{{ old('longitude', config('association.longitude')) }}" />
                            </div>
                            <div class="md:col-span-6 col-span-12">
                                <x-input name="instagram" label="Link do Instagram (Opcional)" type="url" value="{{ old('instagram', config('association.instagram')) }}" />
                            </div>
                            <div class="md:col-span-6 col-span-12">
                                <x-input name="facebook" label="Link do Facebook (Opcional)" type="url" value="{{ old('facebook', config('association.facebook')) }}" />
                            </div>
                            <div class="col-span-12">
                                <x-textarea name="description" label="Sobre / Descrição da Associação" value="{{ old('description', config('association.description')) }}" rows="4" required />
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t flex justify-end">
                            <button type="submit" class="inline-block px-4 py-2 rounded-full font-semibold text-center no-underline text-white shadow-sm" style="background-color: #7a2f1f;">
                                <x-icon name="check" class="w-4 h-4 me-1" /> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Atalhos Rápidos (1/3 de largura) -->
        <div class="lg:col-span-4 col-span-12">
            <div class="bg-white rounded-xl shadow-sm mb-4">
                <div class="p-4">
                    <h5 class="font-bold mb-3" style="color: #7a2f1f;"><x-icon name="user-cog" class="w-4 h-4 me-2" /> Usuários & Permissões</h5>
                    <p class="text-gray-500 text-sm">Gerencie o cadastro de administradores do sistema e visualize quem tem acesso à área protegida.</p>
                    <a href="{{ route('admin.usuarios') }}" class="inline-block px-4 py-2 rounded-full font-semibold text-center no-underline text-white w-full" style="background-color: #5C3A2C;">
                        <x-icon name="sign-out" class="w-4 h-4 me-1" /> Gerenciar Usuários
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm mb-4">
                <div class="p-4">
                    <h5 class="font-bold mb-3" style="color: #7a2f1f;"><x-icon name="cubes" class="w-4 h-4 me-2" /> Backup do Sistema</h5>
                    <p class="text-gray-500 text-sm">Realize o backup completo de todas as tabelas e arquivos armazenados localmente de forma simples.</p>
                    <button class="inline-block px-4 py-2 rounded-full font-semibold text-center no-underline w-full border border-gray-400 text-gray-600 hover:bg-gray-50" disabled>
                        <x-icon name="check-circle" class="w-4 h-4 me-1" /> Módulo Seguro (Em breve)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
