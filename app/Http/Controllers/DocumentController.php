<?php

namespace App\Http\Controllers;

use App\Models\ApprovalStep;
use App\Models\Document;
use App\Models\DocumentAttachment;
use App\Models\DocumentType;
use App\Models\User;
use App\Notifications\DocumentWorkflowNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role?->name;

        $query = Document::with([
            'documentType',
            'creator',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Hak akses berdasarkan role
        |--------------------------------------------------------------------------
        */

        if ($role === 'Staff') {
            $query->where('created_by', $user->id);
        }

        if (in_array($role, ['Supervisor', 'Manager'], true)) {
            $query->whereHas('approvalSteps', function ($approvalQuery) use ($user) {
                $approvalQuery->where('approver_id', $user->id);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Admin dapat melihat semua dokumen
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | Search berdasarkan judul dokumen
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where('title', 'ILIKE', '%' . $search . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | Filter berdasarkan status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        /*
        |--------------------------------------------------------------------------
        | Filter berdasarkan tipe dokumen
        |--------------------------------------------------------------------------
        */

        if ($request->filled('document_type_id')) {
            $query->where(
                'document_type_id',
                $request->input('document_type_id')
            );
        }

        $documents = $query
            ->latest()
            ->get();

        $documentTypes = DocumentType::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('documents.index', [
            'documents' => $documents,
            'documentTypes' => $documentTypes,
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'documentTypeId' => $request->input('document_type_id'),
        ]);
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        $documentTypes = DocumentType::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('documents.create', compact('documentTypes'));
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type_id' => ['required', 'exists:document_types,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        Document::create([
            'document_type_id' => $validated['document_type_id'],
            'created_by' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'] ?? null,
            'status' => 'DRAFT',
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Dokumen berhasil disimpan sebagai draft.');
    }

    /**
     * Display the specified document.
     */
    public function show(Request $request, Document $document)
    {
        $this->authorizeDocumentAccess($request, $document);

        $document->load([
            'documentType',
            'creator',
            'approvalHistories.user',
            'approvalHistories.approvalStep',
            'attachments.uploader',
        ]);

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document)
    {
        if ($document->created_by !== auth()->id()) {
            abort(403);
        }

        if (! in_array($document->status, ['DRAFT', 'REVISION_REQUIRED'], true)) {
            abort(403);
        }

        $documentTypes = DocumentType::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('documents.edit', compact('document', 'documentTypes'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Document $document)
    {
        if ($document->created_by !== auth()->id()) {
            abort(403);
        }

        if (! in_array($document->status, ['DRAFT', 'REVISION_REQUIRED'], true)) {
            abort(403);
        }

        $validated = $request->validate([
            'document_type_id' => ['required', 'exists:document_types,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $document->update([
            'document_type_id' => $validated['document_type_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'] ?? null,
        ]);

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Upload attachment untuk dokumen.
     */
    public function uploadAttachment(Request $request, Document $document)
    {
        if ($document->created_by !== $request->user()->id) {
            abort(403);
        }

        if (! in_array($document->status, ['DRAFT', 'REVISION_REQUIRED'], true)) {
            abort(403);
        }

        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $file = $validated['file'];

        $path = $file->store('documents', 'public');

        DocumentAttachment::create([
            'document_id' => $document->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'File berhasil diupload.');
    }

    /**
     * Download attachment dari dokumen.
     */
    public function downloadAttachment(
        Request $request,
        Document $document,
        DocumentAttachment $attachment
    ) {
        $this->authorizeDocumentAccess($request, $document);

        if ($attachment->document_id !== $document->id) {
            abort(404);
        }

        if (! Storage::disk('public')->exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $attachment->file_path,
            $attachment->file_name
        );
    }

    /**
     * Delete attachment dari dokumen.
     */
    public function deleteAttachment(
        Request $request,
        Document $document,
        DocumentAttachment $attachment
    ) {
        if ($attachment->document_id !== $document->id) {
            abort(404);
        }

        if ($document->created_by !== $request->user()->id) {
            abort(403);
        }

        if (! in_array($document->status, ['DRAFT', 'REVISION_REQUIRED'], true)) {
            abort(403);
        }

        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'Lampiran berhasil dihapus.');
    }

    /**
     * Submit or resubmit the specified document.
     */
    public function submit(Document $document)
    {
        if ($document->created_by !== auth()->id()) {
            abort(403);
        }

        if (! in_array($document->status, ['DRAFT', 'REVISION_REQUIRED'], true)) {
            abort(403);
        }

        DB::transaction(function () use ($document) {

            /*
             * SUBMIT PERTAMA KALI
             */
            if ($document->status === 'DRAFT') {

                $supervisor = User::whereHas('role', function ($query) {
                    $query->where('name', 'Supervisor');
                })
                    ->where('is_active', true)
                    ->firstOrFail();

                $manager = User::whereHas('role', function ($query) {
                    $query->where('name', 'Manager');
                })
                    ->where('is_active', true)
                    ->firstOrFail();

                $document->update([
                    'status' => 'SUBMITTED',
                    'submitted_at' => now(),
                ]);

                ApprovalStep::create([
                    'document_id' => $document->id,
                    'step_order' => 1,
                    'approver_id' => $supervisor->id,
                    'role' => 'Supervisor',
                    'status' => 'PENDING',
                ]);

                ApprovalStep::create([
                    'document_id' => $document->id,
                    'step_order' => 2,
                    'approver_id' => $manager->id,
                    'role' => 'Manager',
                    'status' => 'PENDING',
                ]);

                /*
                 * NOTIFIKASI KE SUPERVISOR
                 */
                $supervisor->notify(
                    new DocumentWorkflowNotification(
                        $document,
                        'Dokumen "' . $document->title . '" menunggu review Anda.'
                    )
                );

                return;
            }

            /*
             * RESUBMIT SETELAH REQUEST REVISION
             */
            $revisionStep = ApprovalStep::where('document_id', $document->id)
                ->where('status', 'REVISION_REQUIRED')
                ->orderBy('step_order')
                ->lockForUpdate()
                ->firstOrFail();

            $revisionStep->update([
                'status' => 'PENDING',
                'acted_at' => null,
                'comment' => null,
            ]);

            $document->update([
                'status' => 'UNDER_REVIEW',
                'submitted_at' => now(),
            ]);
        });

        return redirect()
            ->route('documents.show', $document)
            ->with(
                'success',
                'Dokumen berhasil dikirim kembali untuk proses approval.'
            );
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Document $document)
    {
        //
    }

    /**
     * Memastikan user memiliki hak untuk melihat dokumen.
     */
    private function authorizeDocumentAccess(
        Request $request,
        Document $document
    ): void {
        $user = $request->user();
        $role = $user->role?->name;

        /*
        |--------------------------------------------------------------------------
        | Admin dapat melihat semua dokumen
        |--------------------------------------------------------------------------
        */

        if ($role === 'Admin') {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Staff hanya dapat melihat dokumen miliknya sendiri
        |--------------------------------------------------------------------------
        */

        if ($role === 'Staff') {
            if ($document->created_by === $user->id) {
                return;
            }

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Supervisor dan Manager hanya dapat melihat
        | dokumen yang memiliki approval step untuk mereka
        |--------------------------------------------------------------------------
        */

        if (in_array($role, ['Supervisor', 'Manager'], true)) {
            $hasApprovalStep = $document->approvalSteps()
                ->where('approver_id', $user->id)
                ->exists();

            if ($hasApprovalStep) {
                return;
            }

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Role lain tidak memiliki akses
        |--------------------------------------------------------------------------
        */

        abort(403);
    }
}