<section>
    <header class="mb-4">
        <h2 class="h5 fw-bold" style="color: #7a2f1f;">{{ __('Delete Account') }}</h2>
        <p class="text-muted small">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
    </header>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ __('Delete Account') }}
    </button>

    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body p-4">
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')

                        <h2 class="h5 fw-bold mb-3" style="color: #7a2f1f;">{{ __('Are you sure you want to delete your account?') }}</h2>
                        <p class="text-muted small mb-4">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>

                        <div class="mb-3">
                            <x-input-label for="password" value="{{ __('Password') }}" />
                            <x-text-input id="password" name="password" type="password" placeholder="{{ __('Password') }}" />
                            <x-input-error :messages="$errors->userDeletion->get('password')" />
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('confirm-user-deletion'));
        modal.show();
    });
</script>
@endif
