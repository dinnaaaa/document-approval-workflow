<?php

namespace App\Http\Controllers;

use App\Models\ApprovalHistory;
use App\Models\ApprovalStep;
use App\Notifications\DocumentWorkflowNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    /**
     * Menampilkan dokumen yang menjadi tugas approver yang sedang login.
     */
    public function index(Request $request)
    {
        $pendingSteps = ApprovalStep::with([
            'document.documentType',
            'document.creator',
        ])
            ->where('approver_id', $request->user()->id)
            ->where('status', 'PENDING')
            ->orderBy('created_at')
            ->get();

        return view('approvals.index', compact('pendingSteps'));
    }

    /**
     * Menampilkan detail dokumen yang sedang menunggu approval.
     */
    public function show(Request $request, ApprovalStep $approvalStep)
    {
        if ($approvalStep->approver_id !== $request->user()->id) {
            abort(403);
        }

        if ($approvalStep->role !== $request->user()->role?->name) {
            abort(403);
        }

        if ($approvalStep->status !== 'PENDING') {
            abort(403);
        }

        $approvalStep->load([
            'document.documentType',
            'document.creator',
        ]);

        return view('approvals.show', compact('approvalStep'));
    }

    /**
     * Memproses keputusan approval.
     */
    public function process(Request $request, ApprovalStep $approvalStep)
    {
        if ($approvalStep->approver_id !== $request->user()->id) {
            abort(403);
        }

        if ($approvalStep->role !== $request->user()->role?->name) {
            abort(403);
        }

        if ($approvalStep->status !== 'PENDING') {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi keputusan approval
        |--------------------------------------------------------------------------
        |
        | APPROVE            -> komentar boleh kosong
        | REJECT             -> komentar wajib
        | REQUEST_REVISION   -> komentar wajib
        |
        */

        $validated = $request->validate(
            [
                'action' => [
                    'required',
                    'in:APPROVE,REJECT,REQUEST_REVISION',
                ],

                'comment' => [
                    'required_if:action,REJECT,REQUEST_REVISION',
                    'nullable',
                    'string',
                    'max:2000',
                ],
            ],
            [
                'comment.required_if' =>
                    'Komentar wajib diisi untuk Reject atau Request Revision.',
            ]
        );

        DB::transaction(function () use (
            $approvalStep,
            $validated,
            $request
        ) {

            $document = $approvalStep
                ->document()
                ->lockForUpdate()
                ->firstOrFail();

            if ($approvalStep->status !== 'PENDING') {
                abort(403);
            }

            /*
            |--------------------------------------------------------------------------
            | Pastikan approval berjalan secara berurutan
            |--------------------------------------------------------------------------
            */

            if ($approvalStep->step_order > 1) {

                $previousStep = ApprovalStep::where(
                    'document_id',
                    $document->id
                )
                    ->where(
                        'step_order',
                        $approvalStep->step_order - 1
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($previousStep->status !== 'APPROVED') {
                    abort(403);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | APPROVE
            |--------------------------------------------------------------------------
            */

            if ($validated['action'] === 'APPROVE') {

                $approvalStep->update([
                    'status' => 'APPROVED',
                    'acted_at' => now(),
                    'comment' => $validated['comment'] ?? null,
                ]);

                ApprovalHistory::create([
                    'document_id' => $document->id,
                    'approval_step_id' => $approvalStep->id,
                    'user_id' => $request->user()->id,
                    'action' => 'APPROVE',
                    'comment' => $validated['comment'] ?? null,
                    'created_at' => now(),
                ]);

                $nextStep = ApprovalStep::where(
                    'document_id',
                    $document->id
                )
                    ->where(
                        'step_order',
                        $approvalStep->step_order + 1
                    )
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | Masih ada approval berikutnya
                |--------------------------------------------------------------------------
                */

                if ($nextStep) {

                    $document->update([
                        'status' => 'UNDER_REVIEW',
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Notifikasi ke approver berikutnya
                    |--------------------------------------------------------------------------
                    */

                    $nextApprover = $nextStep->approver;

                    $nextApprover->notify(
                        new DocumentWorkflowNotification(
                            $document,
                            'Dokumen "' .
                            $document->title .
                            '" menunggu approval Anda.'
                        )
                    );

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | FINAL APPROVED
                    |--------------------------------------------------------------------------
                    */

                    $document->update([
                        'status' => 'FINAL_APPROVED',
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Notifikasi final approval ke Staff
                    |--------------------------------------------------------------------------
                    */

                    $document->creator->notify(
                        new DocumentWorkflowNotification(
                            $document,
                            'Dokumen "' .
                            $document->title .
                            '" telah disetujui.'
                        )
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | REJECT
            |--------------------------------------------------------------------------
            */

            if ($validated['action'] === 'REJECT') {

                $approvalStep->update([
                    'status' => 'REJECTED',
                    'acted_at' => now(),
                    'comment' => $validated['comment'],
                ]);

                ApprovalHistory::create([
                    'document_id' => $document->id,
                    'approval_step_id' => $approvalStep->id,
                    'user_id' => $request->user()->id,
                    'action' => 'REJECT',
                    'comment' => $validated['comment'],
                    'created_at' => now(),
                ]);

                $document->update([
                    'status' => 'REJECTED',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Hentikan approval step berikutnya
                |--------------------------------------------------------------------------
                */

                ApprovalStep::where('document_id', $document->id)
                    ->where('id', '!=', $approvalStep->id)
                    ->where('status', 'PENDING')
                    ->update([
                        'status' => 'REJECTED',
                        'acted_at' => now(),
                        'comment' => 'Approval dihentikan karena dokumen ditolak pada step sebelumnya.',
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Notifikasi Reject ke Staff
                |--------------------------------------------------------------------------
                */

                $document->creator->notify(
                    new DocumentWorkflowNotification(
                        $document,
                        'Dokumen "' .
                        $document->title .
                        '" ditolak.'
                    )
                );
            }

            /*
            |--------------------------------------------------------------------------
            | REQUEST REVISION
            |--------------------------------------------------------------------------
            */

            if ($validated['action'] === 'REQUEST_REVISION') {

                $approvalStep->update([
                    'status' => 'REVISION_REQUIRED',
                    'acted_at' => now(),
                    'comment' => $validated['comment'],
                ]);

                ApprovalHistory::create([
                    'document_id' => $document->id,
                    'approval_step_id' => $approvalStep->id,
                    'user_id' => $request->user()->id,
                    'action' => 'REQUEST_REVISION',
                    'comment' => $validated['comment'],
                    'created_at' => now(),
                ]);

                $document->update([
                    'status' => 'REVISION_REQUIRED',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Notifikasi Request Revision ke Staff
                |--------------------------------------------------------------------------
                */

                $document->creator->notify(
                    new DocumentWorkflowNotification(
                        $document,
                        'Dokumen "' .
                        $document->title .
                        '" memerlukan revisi.'
                    )
                );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Otomatis tandai notifikasi terkait dokumen sebagai sudah dibaca
        |--------------------------------------------------------------------------
        */

        $request->user()
            ->unreadNotifications
            ->filter(function ($notification) use ($approvalStep) {
                return (string) data_get($notification->data, 'document_id')
                    === (string) $approvalStep->document_id;
            })
            ->each(function ($notification) {
                $notification->markAsRead();
            });

        return redirect()
            ->route('approvals.index')
            ->with(
                'success',
                'Keputusan approval berhasil diproses.'
            );
    }
}