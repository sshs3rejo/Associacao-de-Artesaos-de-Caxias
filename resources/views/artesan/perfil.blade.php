@extends('layouts.main')
@section('titulo', 'Meu Perfil - Artesão')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-5">
    <a href="{{ route('artesan.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
        <x-icon name="arrow-left" class="w-3 h-3" /> Voltar ao Painel
    </a>
    <div class="flex justify-center">
        <div class="w-full lg:w-2/3">
            <h1 class="font-bold mb-4 text-brand">Meu Perfil de Artesão</h1>

            <x-alert type="success" :message="session('success')" />

            @if(!$perfil->isApproved())
                <x-alert type="warning" message="Seu perfil está aguardando aprovação do administrador." />
            @endif

            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-4">
                    <form action="{{ route('artesan.perfil.atualizar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input name="name" label="Nome" :value="$user->name" required />
                            </div>
                            <div>
                                <x-input name="phone" label="Telefone" :value="$perfil->phone" placeholder="(99) 99999-9999" />
                            </div>
                            <div>
                                <x-input name="specialty" label="Especialidade" :value="$perfil->specialty" placeholder="Ex: Cerâmica, Bordado, Palha..." />
                            </div>
                            <div>
                                <x-input name="whatsapp" label="WhatsApp" :value="$perfil->whatsapp" placeholder="(99) 99999-9999" />
                            </div>
                            <div>
                                <x-input name="instagram" label="Instagram" :value="$perfil->instagram" placeholder="@usuario" />
                            </div>
                            <div>
                                <x-input name="facebook" label="Facebook" :value="$perfil->facebook" placeholder="facebook.com/usuario" />
                            </div>
                            <div class="md:col-span-2">
                                <x-textarea name="bio" label="Bio" :value="$perfil->bio" rows="4" placeholder="Conte um pouco sobre seu trabalho..." />
                            </div>
                            <div>
                                <x-input name="profile_photo" label="Foto de Perfil" type="file" accept="image/*" />
                                @if($perfil->profile_photo)
                                    <div class="mt-2">
                                        <x-image :src="$perfil->profile_photo" alt="Foto" class="rounded-full" style="width: 80px; height: 80px; object-fit: cover;" />
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-end">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_public" value="0">
                                    <input type="checkbox" name="is_public" value="1" class="sr-only peer" role="switch" id="isPublic" {{ $perfil->is_public ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                                    <span class="ml-3">
                                        <span class="font-semibold">Perfil público no site</span>
                                        <small class="block text-gray-500">Seu perfil aparecerá na página de artesãos</small>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="inline-block px-4 py-2 rounded-lg font-semibold px-5 font-bold bg-brand text-accent">
                                <x-icon name="check" class="w-4 h-4 me-2" />Salvar Perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
