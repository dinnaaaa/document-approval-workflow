<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1">
                Detail Approval
            </h2>

            <p class="text-muted mb-0">
                Periksa dokumen sebelum mengambil keputusan approval.
            </p>
        </div>
    </x-slot>

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-12 col-lg-9">

                {{-- DETAIL DOKUMEN --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4 p-md-5">

                        <div class="mb-4">
                            <h4 class="fw-bold mb-1">
                                Detail Dokumen
                            </h4>

                            <p class="text-muted mb-0">
                                Informasi dokumen yang perlu Anda periksa.
                            </p>
                        </div>

                        <div class="row g-3">

                            {{-- JUDUL --}}
                            <div class="col-12">

                                <div class="bg-light rounded p-3">

                                    <div class="text-muted small mb-1">
                                        Judul Dokumen
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $approvalStep->document->title }}
                                    </div>

                                </div>

                            </div>

                            {{-- TIPE --}}
                            <div class="col-12 col-md-6">

                                <div class="bg-light rounded p-3 h-100">

                                    <div class="text-muted small mb-1">
                                        Tipe Dokumen
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $approvalStep->document->documentType->name ?? '-' }}
                                    </div>

                                </div>

                            </div>

                            {{-- DIBUAT OLEH --}}
                            <div class="col-12 col-md-6">

                                <div class="bg-light rounded p-3 h-100">

                                    <div class="text-muted small mb-1">
                                        Dibuat Oleh
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $approvalStep->document->creator->name ?? '-' }}
                                    </div>

                                </div>

                            </div>

                            {{-- DESKRIPSI --}}
                            <div class="col-12">

                                <div class="bg-light rounded p-3">

                                    <div class="text-muted small mb-1">
                                        Deskripsi
                                    </div>

                                    <div>
                                        {{ $approvalStep->document->description ?? '-' }}
                                    </div>

                                </div>

                            </div>

                            {{-- JUMLAH --}}
                            <div class="col-12 col-md-6">

                                <div class="bg-light rounded p-3 h-100">

                                    <div class="text-muted small mb-1">
                                        Jumlah
                                    </div>

                                    <div class="fw-semibold">
                                        Rp {{ number_format($approvalStep->document->amount ?? 0, 0, ',', '.') }}
                                    </div>

                                </div>

                            </div>

                            {{-- STATUS DOKUMEN --}}
                            <div class="col-12 col-md-6">

                                <div class="bg-light rounded p-3 h-100">

                                    <div class="text-muted small mb-1">
                                        Status Dokumen
                                    </div>

                                    @php
                                        $documentStatusBadge = match ($approvalStep->document->status) {
                                            'DRAFT' => 'bg-secondary',
                                            'SUBMITTED' => 'bg-primary',
                                            'UNDER_REVIEW' => 'bg-warning text-dark',
                                            'REVISION_REQUIRED' => 'bg-warning text-dark',
                                            'REJECTED' => 'bg-danger',
                                            'FINAL_APPROVED' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $documentStatusBadge }}">
                                        {{ $approvalStep->document->status }}
                                    </span>

                                </div>

                            </div>

                            {{-- APPROVAL STEP --}}
                            <div class="col-12 col-md-6">

                                <div class="bg-light rounded p-3 h-100">

                                    <div class="text-muted small mb-1">
                                        Approval Step
                                    </div>

                                    <div class="fw-semibold">
                                        Step {{ $approvalStep->step_order }}
                                        - {{ $approvalStep->role }}
                                    </div>

                                </div>

                            </div>

                            {{-- STATUS APPROVAL --}}
                            <div class="col-12 col-md-6">

                                <div class="bg-light rounded p-3 h-100">

                                    <div class="text-muted small mb-1">
                                        Status Approval
                                    </div>

                                    @php
                                        $approvalStatusBadge = match ($approvalStep->status) {
                                            'PENDING' => 'bg-warning text-dark',
                                            'APPROVED' => 'bg-success',
                                            'REJECTED' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $approvalStatusBadge }}">
                                        {{ $approvalStep->status }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- KEPUTUSAN APPROVAL --}}
                @if ($approvalStep->status === 'PENDING')

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-body p-4 p-md-5">

                            <div class="mb-4">

                                <h4 class="fw-bold mb-1">
                                    Keputusan Approval
                                </h4>

                                <p class="text-muted mb-0">
                                    Pilih tindakan yang sesuai terhadap dokumen ini.
                                </p>

                            </div>

                            <form
                                method="POST"
                                action="{{ route('approvals.process', $approvalStep) }}"
                            >
                                @csrf

                                {{-- KOMENTAR --}}
                                <div class="mb-4">

                                    <label
                                        for="comment"
                                        class="form-label fw-semibold"
                                    >
                                        Komentar
                                    </label>

                                    <textarea
                                        id="comment"
                                        name="comment"
                                        rows="4"
                                        class="form-control @error('comment') is-invalid @enderror"
                                        placeholder="Tulis komentar atau alasan keputusan..."
                                    >{{ old('comment') }}</textarea>

                                    @error('comment')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="form-text">
                                        Komentar wajib diisi untuk Reject dan Request Revision.
                                    </div>

                                </div>

                                {{-- ACTION --}}
                                <div class="d-flex flex-wrap gap-2">

                                    <button
                                        type="submit"
                                        name="action"
                                        value="APPROVE"
                                        class="btn btn-success"
                                    >
                                        <i class="bi bi-check-lg me-1"></i>
                                        Approve
                                    </button>

                                    <button
                                        type="submit"
                                        name="action"
                                        value="REJECT"
                                        class="btn btn-danger"
                                    >
                                        <i class="bi bi-x-lg me-1"></i>
                                        Reject
                                    </button>

                                    <button
                                        type="submit"
                                        name="action"
                                        value="REQUEST_REVISION"
                                        class="btn btn-warning"
                                    >
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                                        Request Revision
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                @endif

                {{-- KEMBALI --}}
                <div class="d-flex justify-content-start">

                    <a
                        href="{{ route('approvals.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>