<x-guest-layout>

    <div class="mb-4 text-center">
        <h3 class="fw-bold mb-1">
            Login
        </h3>

        <p class="text-muted mb-0">
            Masuk ke Document Approval Workflow System
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status
        class="mb-3"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- EMAIL --}}
        <div class="mb-3">

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
                autocomplete="username"
                placeholder="Masukkan email"
            >

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">

            <label
                for="password"
                class="form-label fw-semibold"
            >
                Password
            </label>

            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="current-password"
                placeholder="Masukkan password"
            >

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- REMEMBER ME --}}
        <div class="mb-4">

            <div class="form-check">

                <input
                    id="remember_me"
                    type="checkbox"
                    class="form-check-input"
                    name="remember"
                >

                <label
                    for="remember_me"
                    class="form-check-label text-muted"
                >
                    {{ __('Remember me') }}
                </label>

            </div>

        </div>

        {{-- ACTION --}}
        <div class="d-flex flex-column gap-3">

            @if (Route::has('password.request'))

                <div class="text-end">

                    <a
                        href="{{ route('password.request') }}"
                        class="text-decoration-none small"
                    >
                        {{ __('Forgot your password?') }}
                    </a>

                </div>

            @endif

            <button
                type="submit"
                class="btn btn-primary w-100"
            >
                <i class="bi bi-box-arrow-in-right me-1"></i>
                {{ __('Log in') }}
            </button>

        </div>

    </form>

</x-guest-layout>