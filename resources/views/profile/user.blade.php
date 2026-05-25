@extends('layouts.main')

@section('titulo', 'Meu Perfil - ' . config('association.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-5">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
        <x-icon name="arrow-left" class="w-3 h-3" /> Voltar
    </a>
    <div class="flex justify-center">
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-4">
                    <h3 class="font-bold mb-4 text-brand">
                        <x-icon name="user-circle" class="w-5 h-5 me-2" />Meu Perfil
                    </h3>

                    <div class="mb-4">
                        <label class="font-semibold text-gray-500 text-sm">Nome</label>
                        <p class="text-lg mb-3">{{ $user->name }}</p>

                        <label class="font-semibold text-gray-500 text-sm">Email</label>
                        <p class="text-lg mb-3">{{ $user->email }}</p>
                    </div>

                    @if($user->isArtisan() && $profile && !$profile->isApproved())
                        <div class="flex items-center gap-2 bg-blue-100 text-blue-800 px-4 py-3 rounded-lg border border-blue-200 mb-4">
                            <x-icon name="hourglass" class="w-5 h-5 me-2" />
                            Sua solicitação para se tornar artesão está aguardando aprovação do administrador.
                            Você receberá uma notificação quando for aprovado.
                        </div>
                    @elseif(!$user->isArtisan())
                        <hr>
                        <h5 class="font-bold mb-3 text-brand">
                            <x-icon name="hammer" class="w-5 h-5 me-2" />Quero ser artesão
                        </h5>
                        <p class="text-gray-500 mb-3">Preencha os dados abaixo para solicitar seu cadastro como artesão na associação.</p>

                        <form method="POST" action="{{ route('user.tornar-se-artesao') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label for="cpf" class="block font-bold mb-1">CPF <span class="text-red-500">*</span></label>
                                    <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3 @error('cpf') border-red-500 @enderror" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00" maxlength="14" required>
                                    @error('cpf')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label for="telefone" class="block font-bold mb-1">Telefone <span class="text-red-500">*</span></label>
                                    <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3 @error('telefone') border-red-500 @enderror" id="telefone" name="telefone" value="{{ old('telefone') }}" placeholder="(11) 99999-9999" maxlength="20" required>
                                    @error('telefone')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="bio" class="block font-bold mb-1">Biografia / Sobre você</label>
                                    <textarea class="w-full border border-gray-300 rounded-lg px-4 py-3 @error('bio') border-red-500 @enderror" id="bio" name="bio" rows="3" placeholder="Conte um pouco sobre seu trabalho artesanal...">{{ old('bio') }}</textarea>
                                    @error('bio')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label for="foto" class="block font-bold mb-1">Foto de Perfil</label>
                                    <input type="file" class="w-full border border-gray-300 rounded-lg px-4 py-3 @error('foto') border-red-500 @enderror" id="foto" name="foto" accept="image/jpeg,image/png">
                                    @error('foto')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                    <div class="text-gray-500 text-sm mt-1">Aceita JPG, PNG até 2MB.</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="inline-block px-4 py-2 rounded-lg font-semibold text-lg px-5 font-bold rounded-full bg-brand text-accent">
                                    <x-icon name="paper-plane" class="w-5 h-5 me-2" />Fazer Cadastro
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
