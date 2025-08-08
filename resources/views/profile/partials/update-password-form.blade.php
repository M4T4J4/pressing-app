<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">{{ __('Mot de passe actuel') }}</label>
        <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
        @error('current_password', 'updatePassword')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label">{{ __('Nouveau mot de passe') }}</label>
        <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
        @error('password', 'updatePassword')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
        @error('password_confirmation', 'updatePassword')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-4">
        <button type="submit" class="btn btn-primary">{{ __('Enregistrer') }}</button>
        @if (session('status') === 'password-updated')
            <p class="text-success">{{ __('Enregistré.') }}</p>
        @endif
    </div>
</form>