<section>

    <div class="mb-4">

        <h5 class="fw-bold mb-1">
            {{ __('Profile Information') }}
        </h5>

        <p class="text-muted mb-0">
            {{ __("Update your account's profile information and email address.") }}
        </p>

    </div>

    {{-- Verification Form --}}
    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>

    {{-- Profile Update Form --}}
    <form
        method="post"
        action="{{ route('profile.update') }}"
    >
        @csrf
        @method('patch')

        {{-- NAME --}}
        <div class="mb-3">

            <label
                for="name"
                class="form-label fw-semibold"
            >
                {{ __('Name') }}
            </label>

            <input
                id="name"
                name="name"
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            >

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- EMAIL --}}
        <div class="mb-4">

            <label
                for="email"
                class="form-label fw-semibold"
            >
                {{ __('Email') }}
            </label>

            <input
                id="email"
                name="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            >

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            {{-- EMAIL VERIFICATION --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

                <div class="alert alert-warning mt-3 mb-0">

                    <div class="d-flex align-items-start">

                        <i class="bi bi-exclamation-triangle me-2 mt-1"></i>

                        <div>

                            <p class="mb-1">
                                {{ __('Your email address is unverified.') }}
                            </p>

                            <button
                                form="send-verification"
                                type="submit"
                                class="btn btn-link p-0 text-decoration-none"
                            >
                                {{ __('Click here to re-send the verification email.') }}
                            </button>

                        </div>

                    </div>

                    @if (session('status') === 'verification-link-sent')

                        <div class="alert alert-success mt-3 mb-0 py-2">

                            <i class="bi bi-check-circle me-1"></i>

                            {{ __('A new verification link has been sent to your email address.') }}

                        </div>

                    @endif

                </div>

            @endif

        </div>

        {{-- SAVE --}}
        <div class="d-flex align-items-center gap-3">

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-check-lg me-1"></i>
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')

                <span class="text-success small">

                    <i class="bi bi-check-circle me-1"></i>
                    {{ __('Saved.') }}

                </span>

            @endif

        </div>

    </form>

</section>