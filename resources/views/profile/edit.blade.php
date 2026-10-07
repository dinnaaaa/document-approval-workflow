<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1">
                {{ __('Profile') }}
            </h2>

            <p class="text-muted mb-0">
                Kelola informasi profil, password, dan akun Anda.
            </p>
        </div>
    </x-slot>

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-12 col-lg-9">

                {{-- Update Profile Information --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        @include(
                            'profile.partials.update-profile-information-form'
                        )

                    </div>

                </div>

                {{-- Update Password --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        @include(
                            'profile.partials.update-password-form'
                        )

                    </div>

                </div>

                {{-- Delete Account --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        @include(
                            'profile.partials.delete-user-form'
                        )

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>