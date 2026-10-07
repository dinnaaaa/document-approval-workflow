<x-app-layout>

    <x-slot name="header">
        <div>
            <h1 class="h3 mb-1 fw-bold">
                Dashboard
            </h1>

            <p class="text-muted mb-0">
                Selamat datang di Document Approval Workflow System.
            </p>
        </div>
    </x-slot>

    <div class="container py-4">

        {{-- =====================================================
             ADMIN DASHBOARD
        ====================================================== --}}
        @if ($role === 'Admin')

            <div class="mb-4">
                <h2 class="h4 fw-bold mb-1">
                    Dashboard Admin
                </h2>

                <p class="text-muted mb-0">
                    Ringkasan pengguna, dokumen, dan aktivitas approval.
                </p>

                {{-- Navigasi Admin --}}
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('documents.index') }}" class="btn btn-primary">
                        <i class="bi bi-file-earmark-text me-1"></i>
                        Lihat Semua Dokumen
                    </a>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row g-4">

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-2">
                                        Total User
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $totalUsers }}
                                    </h3>
                                </div>

                                <i class="bi bi-people fs-3 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-2">
                                        Total Dokumen
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $totalDocuments }}
                                    </h3>
                                </div>

                                <i class="bi bi-file-earmark-text fs-3 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-2">
                                        Final Approved
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $finalApprovedDocuments }}
                                    </h3>
                                </div>

                                <i class="bi bi-check-circle fs-3 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-2">
                                        Rejected
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $rejectedDocuments }}
                                    </h3>
                                </div>

                                <i class="bi bi-x-circle fs-3 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Document Status --}}
            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <h3 class="h5 fw-bold mb-1">
                        Distribusi Status Dokumen
                    </h3>

                    <p class="text-muted small mb-4">
                        Ringkasan dokumen berdasarkan status saat ini.
                    </p>

                    <div class="row g-3">

                        <div class="col-6 col-md">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">
                                    Draft
                                </div>

                                <div class="fs-4 fw-bold mt-1">
                                    {{ $draftDocuments }}
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">
                                    Review
                                </div>

                                <div class="fs-4 fw-bold mt-1">
                                    {{ $submittedDocuments }}
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">
                                    Revision
                                </div>

                                <div class="fs-4 fw-bold mt-1">
                                    {{ $revisionDocuments }}
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">
                                    Rejected
                                </div>

                                <div class="fs-4 fw-bold mt-1">
                                    {{ $rejectedDocuments }}
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">
                                    Final Approved
                                </div>

                                <div class="fs-4 fw-bold mt-1">
                                    {{ $finalApprovedDocuments }}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Approval Activities --}}
            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <h3 class="h5 fw-bold mb-1">
                        Aktivitas Approval Terbaru
                    </h3>

                    <p class="text-muted small mb-4">
                        Aktivitas approval terbaru dalam sistem.
                    </p>

                    @if ($approvalActivities->isNotEmpty())

                        <div class="list-group list-group-flush">

                            @foreach ($approvalActivities as $activity)

                                <div class="list-group-item px-0 py-3">

                                    <div class="d-flex justify-content-between align-items-start">

                                        <div>
                                            <div class="fw-semibold">
                                                {{ $activity->action }}
                                            </div>

                                            <div class="small text-muted mt-1">
                                                Dokumen:
                                                {{ $activity->document->title ?? '-' }}
                                            </div>

                                            <div class="small text-muted">
                                                Oleh:
                                                {{ $activity->user->name ?? '-' }}
                                            </div>
                                        </div>

                                        <small class="text-muted">
                                            {{ $activity->created_at?->format('d-m-Y H:i') ?? '-' }}
                                        </small>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-4">
                            <i class="bi bi-clock-history fs-2 text-muted"></i>

                            <p class="text-muted mb-0 mt-2">
                                Belum ada aktivitas approval.
                            </p>
                        </div>

                    @endif

                </div>

            </div>


        {{-- =====================================================
             STAFF DASHBOARD
        ====================================================== --}}
        @elseif ($role === 'Staff')

            <div class="mb-4">
                <h2 class="h4 fw-bold mb-1">
                    Dashboard Staff
                </h2>

                <p class="text-muted mb-0">
                    Ringkasan dokumen yang Anda buat.
                </p>

                {{-- Navigasi Staff --}}
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('documents.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Buat Dokumen
                    </a>

                    <a href="{{ route('documents.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-text me-1"></i>
                        Lihat Dokumen Saya
                    </a>
                </div>
            </div>

            <div class="row g-4">

                {{-- Total --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Total Dokumen
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $totalDocuments }}
                                    </h3>
                                </div>

                                <i class="bi bi-file-earmark-text fs-3 text-primary"></i>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Draft --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Draft
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $draftDocuments }}
                                    </h3>
                                </div>

                                <i class="bi bi-file-earmark fs-3 text-secondary"></i>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sedang Diproses --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Sedang Diproses
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $submittedDocuments }}
                                    </h3>
                                </div>

                                <i class="bi bi-hourglass-split fs-3 text-warning"></i>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Revision --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Revision Required
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $revisionDocuments }}
                                    </h3>
                                </div>

                                <i class="bi bi-arrow-repeat fs-3 text-warning"></i>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Rejected --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Rejected
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $rejectedDocuments }}
                                    </h3>
                                </div>

                                <i class="bi bi-x-circle fs-3 text-danger"></i>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Final Approved --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Final Approved
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $finalApprovedDocuments }}
                                    </h3>
                                </div>

                                <i class="bi bi-check-circle fs-3 text-success"></i>

                            </div>
                        </div>
                    </div>
                </div>

            </div>


        {{-- =====================================================
             SUPERVISOR / MANAGER DASHBOARD
        ====================================================== --}}
        @elseif (in_array($role, ['Supervisor', 'Manager'], true))

            <div class="mb-4">
                <h2 class="h4 fw-bold mb-1">
                    Dashboard {{ $role }}
                </h2>

                <p class="text-muted mb-0">
                    Ringkasan proses approval yang menjadi tanggung jawab Anda.
                </p>

                {{-- Navigasi Supervisor / Manager --}}
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('approvals.index') }}" class="btn btn-primary">
                        <i class="bi bi-clipboard-check me-1"></i>
                        Lihat Persetujuan
                    </a>
                </div>
            </div>

            <div class="row g-4">

                {{-- Pending --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Menunggu Approval
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $pendingApprovals }}
                                    </h3>
                                </div>

                                <i class="bi bi-hourglass-split fs-3 text-warning"></i>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Approved --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Approved
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $approvedApprovals }}
                                    </h3>
                                </div>

                                <i class="bi bi-check-circle fs-3 text-success"></i>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Rejected --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Rejected
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $rejectedApprovals }}
                                    </h3>
                                </div>

                                <i class="bi bi-x-circle fs-3 text-danger"></i>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Revision --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div>
                                    <p class="text-muted small mb-2">
                                        Revision
                                    </p>

                                    <h3 class="fw-bold mb-0">
                                        {{ $revisionApprovals }}
                                    </h3>
                                </div>

                                <i class="bi bi-arrow-repeat fs-3 text-warning"></i>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            @if ($role === 'Manager')

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-muted small mb-2">
                                    Total Dokumen Final Approved
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ $finalApprovedDocuments }}
                                </h3>
                            </div>

                            <i class="bi bi-patch-check fs-2 text-success"></i>

                        </div>

                    </div>

                </div>

            @endif

        @endif

    </div>

</x-app-layout>