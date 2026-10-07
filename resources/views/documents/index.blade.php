<x-app-layout>

    {{-- =========================
         PAGE HEADER
    ========================== --}}
    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1">
                Daftar Dokumen
            </h2>

            <p class="text-muted mb-0">
                Kelola dan lihat dokumen yang tersedia.
            </p>
        </div>
    </x-slot>


    <div class="container py-4">

        {{-- =========================
             ACTION HEADER
        ========================== --}}
        <div class="d-flex justify-content-end mb-4">

            <a
                href="{{ route('documents.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Buat Dokumen
            </a>

        </div>


        {{-- =========================
             SUCCESS MESSAGE
        ========================== --}}
        @if (session('success'))

            <div
                class="alert alert-success mb-4"
                role="alert"
            >
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>

        @endif


        {{-- =========================
             SEARCH & FILTER
        ========================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body p-4">

                <div class="mb-4">

                    <h4 class="fw-bold mb-1">
                        Search & Filter
                    </h4>

                    <p class="text-muted mb-0">
                        Gunakan satu atau beberapa filter secara bersamaan untuk menemukan dokumen.
                    </p>

                </div>


                <form
                    method="GET"
                    action="{{ route('documents.index') }}"
                >

                    <div class="row g-3">

                        {{-- =========================
                             SEARCH
                        ========================== --}}
                        <div class="col-12 col-md-4">

                            <label
                                for="search"
                                class="form-label fw-semibold"
                            >
                                Cari Judul Dokumen
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ $search ?? '' }}"
                                placeholder="Contoh: keyboard"
                                class="form-control"
                            >

                        </div>


                        {{-- =========================
                             STATUS
                        ========================== --}}
                        <div class="col-12 col-md-4">

                            <label
                                for="status"
                                class="form-label fw-semibold"
                            >
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="form-select"
                            >

                                <option value="">
                                    Semua Status
                                </option>

                                <option
                                    value="DRAFT"
                                    @selected(($status ?? '') === 'DRAFT')
                                >
                                    DRAFT
                                </option>

                                <option
                                    value="SUBMITTED"
                                    @selected(($status ?? '') === 'SUBMITTED')
                                >
                                    SUBMITTED
                                </option>

                                <option
                                    value="UNDER_REVIEW"
                                    @selected(($status ?? '') === 'UNDER_REVIEW')
                                >
                                    UNDER_REVIEW
                                </option>

                                <option
                                    value="REVISION_REQUIRED"
                                    @selected(($status ?? '') === 'REVISION_REQUIRED')
                                >
                                    REVISION_REQUIRED
                                </option>

                                <option
                                    value="REJECTED"
                                    @selected(($status ?? '') === 'REJECTED')
                                >
                                    REJECTED
                                </option>

                                <option
                                    value="FINAL_APPROVED"
                                    @selected(($status ?? '') === 'FINAL_APPROVED')
                                >
                                    FINAL_APPROVED
                                </option>

                            </select>

                        </div>


                        {{-- =========================
                             DOCUMENT TYPE
                        ========================== --}}
                        <div class="col-12 col-md-4">

                            <label
                                for="document_type_id"
                                class="form-label fw-semibold"
                            >
                                Tipe Dokumen
                            </label>

                            <select
                                id="document_type_id"
                                name="document_type_id"
                                class="form-select"
                            >

                                <option value="">
                                    Semua Tipe
                                </option>

                                @foreach ($documentTypes as $documentType)

                                    <option
                                        value="{{ $documentType->id }}"
                                        @selected(
                                            (string) ($documentTypeId ?? '') ===
                                            (string) $documentType->id
                                        )
                                    >
                                        {{ $documentType->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- =========================
                         FILTER BUTTONS
                    ========================== --}}
                    <div class="d-flex flex-wrap gap-2 mt-4">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-search me-1"></i>
                            Cari / Filter
                        </button>

                        <a
                            href="{{ route('documents.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>

                    </div>

                </form>

            </div>

        </div>


        {{-- =========================
             DOCUMENT LIST
        ========================== --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <div class="mb-4">

                    <h4 class="fw-bold mb-1">
                        Hasil Dokumen
                    </h4>

                    <p class="text-muted mb-0">
                        {{ $documents->count() }} dokumen ditemukan.
                    </p>

                </div>


                {{-- =========================
                     DOCUMENT TABLE
                ========================== --}}
                @if ($documents->isNotEmpty())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="px-3 py-3">
                                        Judul
                                    </th>

                                    <th class="px-3 py-3">
                                        Tipe
                                    </th>

                                    <th class="px-3 py-3">
                                        Dibuat Oleh
                                    </th>

                                    <th class="px-3 py-3">
                                        Status
                                    </th>

                                    <th class="px-3 py-3">
                                        Tanggal
                                    </th>

                                    <th class="px-3 py-3 text-center">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($documents as $document)

                                    <tr>

                                        {{-- JUDUL --}}
                                        <td class="px-3 py-3">

                                            <div class="fw-semibold text-dark">
                                                {{ $document->title }}
                                            </div>

                                        </td>


                                        {{-- TIPE --}}
                                        <td class="px-3 py-3 text-muted">

                                            {{ $document->documentType->name ?? '-' }}

                                        </td>


                                        {{-- PEMBUAT --}}
                                        <td class="px-3 py-3 text-muted">

                                            {{ $document->creator->name ?? '-' }}

                                        </td>


                                        {{-- STATUS --}}
                                        <td class="px-3 py-3">

                                            @php
                                                $statusBadge = match ($document->status) {
                                                    'DRAFT' => 'bg-secondary',
                                                    'SUBMITTED' => 'bg-primary',
                                                    'UNDER_REVIEW' => 'bg-warning text-dark',
                                                    'REVISION_REQUIRED' => 'bg-warning text-dark',
                                                    'REJECTED' => 'bg-danger',
                                                    'FINAL_APPROVED' => 'bg-success',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp

                                            <span class="badge {{ $statusBadge }}">
                                                {{ $document->status }}
                                            </span>

                                        </td>


                                        {{-- TANGGAL --}}
                                        <td class="px-3 py-3 text-muted">

                                            {{ $document->created_at->format('d-m-Y H:i') }}

                                        </td>


                                        {{-- AKSI --}}
                                        <td class="px-3 py-3 text-center">

                                            <a
                                                href="{{ route('documents.show', $document) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                <i class="bi bi-eye me-1"></i>
                                                Lihat
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                {{-- =========================
                     EMPTY STATE
                ========================== --}}
                @else

                    <div class="alert alert-light border text-center py-4 mb-0">

                        <i class="bi bi-file-earmark-x fs-3 text-muted d-block mb-2"></i>

                        <div class="fw-semibold">
                            Tidak ada dokumen
                        </div>

                        <div class="text-muted small">
                            Tidak ada dokumen yang sesuai dengan pencarian atau filter.
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>