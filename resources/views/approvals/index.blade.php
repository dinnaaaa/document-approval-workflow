<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1">
                Tugas Approval
            </h2>

            <p class="text-muted mb-0">
                Kelola dokumen yang menjadi tugas approval Anda.
            </p>
        </div>
    </x-slot>

    <div class="container py-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fw-bold mb-1">
                        Dokumen Menunggu Approval
                    </h4>

                    <p class="text-muted mb-0">
                        Daftar dokumen yang menjadi tugas Anda.
                    </p>
                </div>

                @if ($pendingSteps->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="px-3 py-3">
                                        No
                                    </th>

                                    <th class="px-3 py-3">
                                        Judul Dokumen
                                    </th>

                                    <th class="px-3 py-3">
                                        Tipe
                                    </th>

                                    <th class="px-3 py-3">
                                        Dibuat Oleh
                                    </th>

                                    <th class="px-3 py-3 text-center">
                                        Step
                                    </th>

                                    <th class="px-3 py-3">
                                        Status
                                    </th>

                                    <th class="px-3 py-3 text-center">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($pendingSteps as $step)

                                    <tr>

                                        <td class="px-3 py-3">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="px-3 py-3">

                                            <div class="fw-semibold text-dark">
                                                {{ $step->document->title }}
                                            </div>

                                        </td>

                                        <td class="px-3 py-3 text-muted">
                                            {{ $step->document->documentType->name ?? '-' }}
                                        </td>

                                        <td class="px-3 py-3 text-muted">
                                            {{ $step->document->creator->name ?? '-' }}
                                        </td>

                                        <td class="px-3 py-3 text-center">

                                            <span class="badge bg-primary">
                                                Step {{ $step->step_order }}
                                            </span>

                                        </td>

                                        <td class="px-3 py-3">

                                            @php
                                                $statusBadge = match ($step->status) {
                                                    'PENDING' => 'bg-warning text-dark',
                                                    'APPROVED' => 'bg-success',
                                                    'REJECTED' => 'bg-danger',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp

                                            <span class="badge {{ $statusBadge }}">
                                                {{ $step->status }}
                                            </span>

                                        </td>

                                        <td class="px-3 py-3 text-center">

                                            <a
                                                href="{{ route('approvals.show', $step) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                <i class="bi bi-eye me-1"></i>
                                                Detail
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="bi bi-check2-circle fs-1 text-muted d-block mb-3"></i>

                        <div class="fw-semibold mb-1">
                            Tidak ada tugas approval
                        </div>

                        <p class="text-muted mb-0">
                            Tidak ada dokumen yang menunggu approval Anda.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>