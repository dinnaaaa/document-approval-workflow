<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1">
                Tambah Dokumen
            </h2>

            <p class="text-muted mb-0">
                Buat dokumen baru dan simpan sebagai draft.
            </p>
        </div>
    </x-slot>

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <div class="mb-4">
                            <h4 class="fw-bold mb-1">
                                Form Tambah Dokumen
                            </h4>

                            <p class="text-muted mb-0">
                                Lengkapi informasi dokumen berikut.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('documents.store') }}">
                            @csrf

                            {{-- TIPE DOKUMEN --}}
                            <div class="mb-4">

                                <label
                                    for="document_type_id"
                                    class="form-label fw-semibold"
                                >
                                    Tipe Dokumen
                                </label>

                                <select
                                    id="document_type_id"
                                    name="document_type_id"
                                    class="form-select @error('document_type_id') is-invalid @enderror"
                                >
                                    <option value="">
                                        -- Pilih Tipe Dokumen --
                                    </option>

                                    @foreach ($documentTypes as $documentType)
                                        <option
                                            value="{{ $documentType->id }}"
                                            @selected(old('document_type_id') == $documentType->id)
                                        >
                                            {{ $documentType->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('document_type_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- JUDUL --}}
                            <div class="mb-4">

                                <label
                                    for="title"
                                    class="form-label fw-semibold"
                                >
                                    Judul Dokumen
                                </label>

                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Masukkan judul dokumen"
                                >

                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- DESKRIPSI --}}
                            <div class="mb-4">

                                <label
                                    for="description"
                                    class="form-label fw-semibold"
                                >
                                    Deskripsi
                                </label>

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Masukkan deskripsi dokumen"
                                >{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- NOMINAL --}}
                            <div class="mb-4">

                                <label
                                    for="amount"
                                    class="form-label fw-semibold"
                                >
                                    Jumlah / Nominal
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        Rp
                                    </span>

                                    <input
                                        type="number"
                                        id="amount"
                                        name="amount"
                                        value="{{ old('amount') }}"
                                        step="0.01"
                                        min="0"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        placeholder="Contoh: 5000000"
                                    >

                                    @error('amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                            {{-- BUTTON --}}
                            <div class="d-flex flex-wrap gap-2 pt-2">

                                <a
                                    href="{{ route('documents.index') }}"
                                    class="btn btn-outline-secondary"
                                >
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Batal
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="bi bi-save me-1"></i>
                                    Simpan sebagai Draft
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>