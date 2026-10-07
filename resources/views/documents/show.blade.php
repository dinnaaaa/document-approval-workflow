<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1">
                Detail Dokumen
            </h2>

            <p class="text-muted mb-0">
                Informasi lengkap dan proses dokumen.
            </p>
        </div>
    </x-slot>


    <div class="container py-4">

        {{-- =====================================================
             DETAIL DOKUMEN
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body p-4">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

                    <div>
                        <h4 class="fw-bold mb-1">
                            Detail Dokumen
                        </h4>

                        <p class="text-muted mb-0">
                            Informasi lengkap dokumen.
                        </p>
                    </div>

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

                    <span class="badge {{ $statusBadge }} fs-6 px-3 py-2">
                        {{ $document->status }}
                    </span>

                </div>


                {{-- Pesan sukses --}}
                @if (session('success'))
                    <div class="alert alert-success mb-4" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif


                {{-- Informasi dokumen --}}
                <div class="row g-4">

                    {{-- Tipe --}}
                    <div class="col-12 col-md-6">

                        <div class="bg-light rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Tipe Dokumen
                            </div>

                            <div class="fw-semibold">
                                {{ $document->documentType->name ?? '-' }}
                            </div>

                        </div>

                    </div>


                    {{-- Judul --}}
                    <div class="col-12 col-md-6">

                        <div class="bg-light rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Judul Dokumen
                            </div>

                            <div class="fw-semibold">
                                {{ $document->title }}
                            </div>

                        </div>

                    </div>


                    {{-- Deskripsi --}}
                    <div class="col-12">

                        <div class="bg-light rounded p-3">

                            <div class="text-muted small mb-1">
                                Deskripsi
                            </div>

                            <div>
                                {{ $document->description ?: '-' }}
                            </div>

                        </div>

                    </div>


                    {{-- Nominal --}}
                    <div class="col-12 col-md-6">

                        <div class="bg-light rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Jumlah / Nominal
                            </div>

                            <div class="fw-semibold">
                                {{ $document->amount !== null
                                    ? 'Rp ' . number_format($document->amount, 2, ',', '.')
                                    : '-' }}
                            </div>

                        </div>

                    </div>


                    {{-- Dibuat oleh --}}
                    <div class="col-12 col-md-6">

                        <div class="bg-light rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Dibuat Oleh
                            </div>

                            <div class="fw-semibold">
                                {{ $document->creator->name ?? '-' }}
                            </div>

                        </div>

                    </div>


                    {{-- Dibuat pada --}}
                    <div class="col-12 col-md-6">

                        <div class="bg-light rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Dibuat Pada
                            </div>

                            <div class="fw-semibold">
                                {{ $document->created_at->format('d-m-Y H:i') }}
                            </div>

                        </div>

                    </div>


                    {{-- Terakhir diperbarui --}}
                    <div class="col-12 col-md-6">

                        <div class="bg-light rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Terakhir Diperbarui
                            </div>

                            <div class="fw-semibold">
                                {{ $document->updated_at->format('d-m-Y H:i') }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     ACTION BUTTONS
                ====================================================== --}}
                <div class="d-flex flex-wrap gap-2 mt-4">

                    {{-- Edit --}}
                    @if (in_array($document->status, ['DRAFT', 'REVISION_REQUIRED'], true))

                        <a
                            href="{{ route('documents.edit', $document) }}"
                            class="btn btn-warning"
                        >
                            <i class="bi bi-pencil me-1"></i>
                            Edit Dokumen
                        </a>

                    @endif


                    {{-- Submit --}}
                    @if ($document->status === 'DRAFT')

                        <form
                            method="POST"
                            action="{{ route('documents.submit', $document) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-send me-1"></i>
                                Submit Dokumen
                            </button>

                        </form>

                    @endif


                    {{-- Resubmit --}}
                    @if ($document->status === 'REVISION_REQUIRED')

                        <form
                            method="POST"
                            action="{{ route('documents.submit', $document) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-arrow-repeat me-1"></i>
                                Resubmit Dokumen
                            </button>

                        </form>

                    @endif


                    {{-- Kembali --}}
                    <a
                        href="{{ route('documents.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali ke Daftar Dokumen
                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ATTACHMENTS
        ====================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body p-4">

                <div class="mb-4">

                    <h4 class="fw-bold mb-1">
                        Lampiran Dokumen
                    </h4>

                    <p class="text-muted mb-0">
                        File pendukung yang terkait dengan dokumen ini.
                    </p>

                </div>


                {{-- Form upload --}}
                @if (
                    in_array($document->status, ['DRAFT', 'REVISION_REQUIRED'], true)
                    && $document->created_by === auth()->id()
                )

                    <div class="bg-light border rounded p-4 mb-4">

                        <h5 class="fw-bold mb-2">
                            Upload File
                        </h5>

                        <p class="text-muted small mb-3">
                            Format yang diperbolehkan:
                            PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.
                            Maksimal 5 MB per file.
                        </p>


                        <form
                            method="POST"
                            action="{{ route('documents.attachments.upload', $document) }}"
                            enctype="multipart/form-data"
                        >
                            @csrf

                            <div class="row g-2">

                                <div class="col-12 col-md-9">

                                    <input
                                        type="file"
                                        name="file"
                                        required
                                        class="form-control"
                                    >

                                </div>

                                <div class="col-12 col-md-3">

                                    <button
                                        type="submit"
                                        class="btn btn-primary w-100"
                                    >
                                        <i class="bi bi-upload me-1"></i>
                                        Upload File
                                    </button>

                                </div>

                            </div>


                            @error('file')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror

                        </form>

                    </div>

                @endif


                {{-- Daftar attachment --}}
                @if ($document->attachments->isNotEmpty())

                    <div class="list-group">

                        @foreach ($document->attachments as $attachment)

                            <div class="list-group-item p-3">

                                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">

                                    <div class="min-width-0">

                                        <div class="fw-semibold text-break">
                                            <i class="bi bi-paperclip me-1"></i>
                                            {{ $attachment->file_name }}
                                        </div>

                                        <div class="text-muted small mt-1">

                                            {{ strtoupper($attachment->file_type) }}

                                            ·

                                            {{ number_format($attachment->file_size / 1024, 2) }}
                                            KB

                                        </div>

                                        <div class="text-muted small mt-1">

                                            Diupload oleh:
                                            {{ $attachment->uploader->name ?? '-' }}

                                        </div>

                                        <div class="text-muted small">

                                            {{ $attachment->created_at?->format('d-m-Y H:i') ?? '-' }}

                                        </div>

                                    </div>


                                    <div class="d-flex flex-wrap gap-2 align-items-center">

                                        {{-- Download --}}
                                        <a
                                            href="{{ route('documents.attachments.download', [$document, $attachment]) }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            <i class="bi bi-download me-1"></i>
                                            Download
                                        </a>


                                        {{-- Hapus --}}
                                        @if (
                                            $document->created_by === auth()->id()
                                            && in_array($document->status, ['DRAFT', 'REVISION_REQUIRED'], true)
                                        )

                                            <form
                                                method="POST"
                                                action="{{ route('documents.attachments.delete', [$document, $attachment]) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus lampiran ini?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-danger"
                                                >
                                                    <i class="bi bi-trash me-1"></i>
                                                    Hapus
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="alert alert-light border text-center mb-0">

                        <i class="bi bi-file-earmark-x fs-3 text-muted d-block mb-2"></i>

                        <div class="fw-semibold">
                            Belum ada lampiran
                        </div>

                        <div class="text-muted small">
                            Belum ada file lampiran untuk dokumen ini.
                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
             PROSES APPROVAL
        ====================================================== --}}
        @if (
            auth()->user()->role?->name === 'Supervisor' ||
            auth()->user()->role?->name === 'Manager'
        )

            @php
                $pendingApprovalStep = $document->approvalSteps()
                    ->where('approver_id', auth()->id())
                    ->where('status', 'PENDING')
                    ->orderBy('step_order')
                    ->first();
            @endphp


            @if ($pendingApprovalStep)

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <div class="mb-4">

                            <h4 class="fw-bold mb-1">
                                Proses Approval
                            </h4>

                            <p class="text-muted mb-0">
                                Dokumen ini menunggu keputusan Anda sebagai
                                {{ $pendingApprovalStep->role }}.
                            </p>

                        </div>


                        <form
                            method="POST"
                            action="{{ route('approvals.process', $pendingApprovalStep) }}"
                        >
                            @csrf


                            {{-- Komentar --}}
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
                                    class="form-control"
                                    placeholder="Tambahkan komentar jika diperlukan..."
                                >{{ old('comment') }}</textarea>

                                @error('comment')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Tombol --}}
                            <div class="d-flex flex-wrap gap-2">

                                {{-- Approve --}}
                                <button
                                    type="submit"
                                    name="action"
                                    value="APPROVE"
                                    class="btn btn-success"
                                >
                                    <i class="bi bi-check-lg me-1"></i>
                                    Approve
                                </button>


                                {{-- Request Revision --}}
                                <button
                                    type="submit"
                                    name="action"
                                    value="REQUEST_REVISION"
                                    class="btn btn-warning"
                                >
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                    Request Revision
                                </button>


                                {{-- Reject --}}
                                <button
                                    type="submit"
                                    name="action"
                                    value="REJECT"
                                    class="btn btn-danger"
                                >
                                    <i class="bi bi-x-lg me-1"></i>
                                    Reject
                                </button>

                            </div>


                            <div class="text-muted small mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Komentar wajib diisi untuk Reject dan Request Revision.
                            </div>

                        </form>

                    </div>

                </div>

            @endif

        @endif


        {{-- =====================================================
             RIWAYAT APPROVAL
        ====================================================== --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <div class="mb-4">

                    <h4 class="fw-bold mb-1">
                        Riwayat Approval
                    </h4>

                    <p class="text-muted mb-0">
                        Riwayat tindakan approval yang dilakukan terhadap dokumen.
                    </p>

                </div>


                @if ($document->approvalHistories->isNotEmpty())

                    <div class="d-flex flex-column gap-3">

                        @foreach ($document->approvalHistories->sortBy('created_at') as $history)

                            @php
                                $historyBadge = match ($history->action) {
                                    'APPROVE' => 'bg-success',
                                    'REJECT' => 'bg-danger',
                                    'REQUEST_REVISION' => 'bg-warning text-dark',
                                    default => 'bg-secondary',
                                };
                            @endphp

                            <div class="border rounded p-4">

                                <div class="d-flex flex-column flex-md-row justify-content-between gap-2">

                                    <div>

                                        <div class="d-flex align-items-center gap-2 flex-wrap">

                                            <span class="badge {{ $historyBadge }}">
                                                {{ $history->action }}
                                            </span>

                                            <span class="fw-semibold">
                                                {{ $history->user->name ?? '-' }}
                                            </span>

                                        </div>

                                        <div class="text-muted small mt-2">
                                            {{ $history->user->name ?? '-' }}
                                        </div>

                                    </div>


                                    <div class="text-muted small">
                                        {{ $history->created_at?->format('d-m-Y H:i') ?? '-' }}
                                    </div>

                                </div>


                                <div class="row g-3 mt-2">

                                    <div class="col-12 col-md-6">

                                        <div class="bg-light rounded p-3">

                                            <div class="text-muted small">
                                                Approval Step
                                            </div>

                                            <div class="fw-semibold mt-1">

                                                @if ($history->approvalStep)

                                                    Step
                                                    {{ $history->approvalStep->step_order }}

                                                    -
                                                    
                                                    {{ $history->approvalStep->role }}

                                                @else
                                                    -
                                                @endif

                                            </div>

                                        </div>

                                    </div>


                                    <div class="col-12 col-md-6">

                                        <div class="bg-light rounded p-3">

                                            <div class="text-muted small">
                                                Tindakan Oleh
                                            </div>

                                            <div class="fw-semibold mt-1">
                                                {{ $history->user->name ?? '-' }}
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <div class="mt-3">

                                    <div class="text-muted small fw-semibold mb-1">
                                        Komentar
                                    </div>

                                    <div class="bg-light rounded p-3">
                                        {{ $history->comment ?: '-' }}
                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="alert alert-light border text-center mb-0">

                        <i class="bi bi-clock-history fs-3 text-muted d-block mb-2"></i>

                        <div class="fw-semibold">
                            Belum ada riwayat approval
                        </div>

                        <div class="text-muted small">
                            Belum ada riwayat approval untuk dokumen ini.
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>