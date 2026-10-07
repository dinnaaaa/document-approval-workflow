<x-guest-layout>

    <div class="mb-4 text-center">

        <h3 class="fw-bold mb-2">
            Reset Password
        </h3>

        <p class="text-muted mb-0">
            Buat password baru untuk akun Anda.
        </p>

    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- PASSWORD RESET TOKEN --}}
        <input
            type="hidden"
            name="token"
            value="{{ $request->route('token') }}"
        >

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
                value="{{ old('email', $request->email) }}"
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
                Password Baru
            </label>

            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="new-password"
                placeholder="Masukkan password baru"
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
                Konfirmasi Password Baru
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                required
                autocomplete="new-password"
                placeholder="Ulangi password baru"
            >

            @error('password_confirmation')
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
            <i class="bi bi-key me-1"></i>
            {{ __('Reset Password') }}
        </button>

    </form>

</x-guest-layout>