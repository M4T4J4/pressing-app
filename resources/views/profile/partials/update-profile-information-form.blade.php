<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Nom') }}</label>
        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
        @error('name')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email') }}</label>
        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
        @error('email')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="alert alert-warning">
            <p>{{ __('Votre adresse email n\'est pas vérifiée.') }}</p>
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}</button>
            </form>
        </div>
    @endif

    <div class="d-flex align-items-center gap-4">
        <button type="submit" class="btn btn-primary">{{ __('Enregistrer') }}</button>
        @if (session('status') === 'profile-updated')
            <p class="text-success">{{ __('Enregistré.') }}</p>
        @endif
    </div>
</form>