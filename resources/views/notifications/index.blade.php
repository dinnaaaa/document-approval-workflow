<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1">
                Notifikasi
            </h2>

            <p class="text-muted mb-0">
                Informasi terbaru mengenai proses dokumen Anda.
            </p>
        </div>
    </x-slot>

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-12 col-lg-9">

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <div class="mb-4">

                            <h4 class="fw-bold mb-1">
                                Notifikasi
                            </h4>

                            <p class="text-muted mb-0">
                                Informasi terbaru mengenai proses dokumen Anda.
                            </p>

                        </div>

                        @if ($notifications->isNotEmpty())

                            <div class="d-flex flex-column gap-3">

                                @foreach ($notifications as $notification)

                                    <div
                                        class="border rounded p-4
                                        {{ $notification->read_at
                                            ? 'border-light bg-white'
                                            : 'border-primary bg-primary bg-opacity-10' }}"
                                    >

                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">

                                            {{-- INFORMASI NOTIFIKASI --}}
                                            <div class="flex-grow-1">

                                                <div class="d-flex align-items-center gap-2">

                                                    @if (!$notification->read_at)

                                                        <span
                                                            class="badge rounded-pill bg-primary"
                                                            title="Belum dibaca"
                                                        >
                                                            Baru
                                                        </span>

                                                    @endif

                                                    <div class="fw-semibold text-dark">
                                                        {{ $notification->data['document_title'] ?? 'Notifikasi Dokumen' }}
                                                    </div>

                                                </div>

                                                <div class="text-muted mt-2">
                                                    {{ $notification->data['message'] ?? '-' }}
                                                </div>

                                                <div class="text-muted small mt-2">

                                                    <i class="bi bi-clock me-1"></i>

                                                    {{ $notification->created_at?->format('d-m-Y H:i') ?? '-' }}

                                                </div>

                                            </div>

                                            {{-- STATUS / ACTION --}}
                                            <div class="flex-shrink-0">

                                                @if (!$notification->read_at)

                                                    <form
                                                        method="POST"
                                                        action="{{ route('notifications.read', $notification->id) }}"
                                                    >
                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="btn btn-primary btn-sm"
                                                        >
                                                            <i class="bi bi-check2 me-1"></i>
                                                            Tandai Dibaca
                                                        </button>

                                                    </form>

                                                @else

                                                    <span class="badge bg-light text-secondary border px-3 py-2">

                                                        <i class="bi bi-check2-circle me-1"></i>
                                                        Sudah Dibaca

                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="text-center py-5 bg-light border rounded">

                                <i class="bi bi-bell-slash fs-1 text-muted d-block mb-3"></i>

                                <div class="fw-semibold mb-1">
                                    Belum ada notifikasi
                                </div>

                                <p class="text-muted mb-0">
                                    Belum ada informasi terbaru mengenai proses dokumen Anda.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>