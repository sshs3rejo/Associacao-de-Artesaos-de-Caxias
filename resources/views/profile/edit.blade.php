<x-app-layout>
    <x-slot name="header">Perfil</x-slot>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-5">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-5">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-5">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
