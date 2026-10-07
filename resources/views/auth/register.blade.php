<x-guest-layout>

    <div class="mb-4 text-center">
        <h3 class="fw-bold mb-1">
            Register
        </h3>

        <p class="text-muted mb-0">
            Buat akun Document Approval Workflow System
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- NAME --}}
        <div class="mb-3">

            <label
                for="name"
                class="form-label fw-semibold"
            >
                Name
            </label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror"
                required
                autofocus
                autocomplete="name"
                placeholder="Masukkan nama"
            >

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

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
                autocomplete="new-password"
                placeholder="Masukkan password"
            >

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- CONFIRM PASSWORD --}}
        <div class="mb-4">

            <label
                for="password_confirmation"
                class="form-label fw-semibold"
            >
                Confirm Password
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                required
                autocomplete="new-password"
                placeholder="Konfirmasi password"
            >

            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- ACTION --}}
        <div class="d-flex flex-column gap-3">

            <button
                type="submit"
                class="btn btn-primary w-100"
            >
                <i class="bi bi-person-plus me-1"></i>
                {{ __('Register') }}
            </button>

            <div class="text-center">

                <span class="text-muted small">
                    Sudah memiliki akun?
                </span>

                <a
                    href="{{ route('login') }}"
                    class="text-decoration-none small fw-semibold"
                >
                    {{ __('Login') }}
                </a>

            </div>

        </div>

    </form>

</x-guest-layout>