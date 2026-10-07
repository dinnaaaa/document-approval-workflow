<x-guest-layout>

    <div class="mb-4 text-center">

        <h3 class="fw-bold mb-2">
            Lupa Password?
        </h3>

        <p class="text-muted mb-0">
            Masukkan email Anda dan kami akan mengirimkan
            link untuk membuat password baru.
        </p>

    </div>

    {{-- Session Status --}}
    <x-auth-session-status
        class="mb-3"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- EMAIL --}}
        <div class="mb-4">

            <label
                for="email"
                class="form-label fw-semibold"
            >
                Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required
                autofocus
                placeholder="Masukkan email"
            >

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- BUTTON --}}
        <button
            type="submit"
            class="btn btn-primary w-100"
        >
            <i class="bi bi-envelope me-1"></i>
            {{ __('Email Password Reset Link') }}
        </button>

    </form>

    <div class="text-center mt-3">

        <a
            href="{{ route('login') }}"
            class="text-decoration-none small"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Login
        </a>

    </div>

</x-guest-layout>