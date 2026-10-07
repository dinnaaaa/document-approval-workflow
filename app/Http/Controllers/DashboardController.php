<?php

namespace App\Http\Controllers;

use App\Models\ApprovalHistory;
use App\Models\ApprovalStep;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role?->name;

        $data = [
            'role' => $role,
        ];

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($role === 'Admin') {
            $data['totalUsers'] = User::count();

            $data['totalDocuments'] = Document::count();

            $data['draftDocuments'] = Document::where('status', 'DRAFT')->count();

            $data['submittedDocuments'] = Document::whereIn('status', [
                'SUBMITTED',
                'UNDER_REVIEW',
            ])->count();

            $data['revisionDocuments'] = Document::where(
                'status',
                'REVISION_REQUIRED'
            )->count();

            $data['rejectedDocuments'] = Document::where(
                'status',
                'REJECTED'
            )->count();

            $data['finalApprovedDocuments'] = Document::where(
                'status',
                'FINAL_APPROVED'
            )->count();

            $data['approvalActivities'] = ApprovalHistory::with([
                'document',
                'user',
            ])
                ->latest('created_at')
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | STAFF
        |--------------------------------------------------------------------------
        */

        if ($role === 'Staff') {
            $documents = Document::where('created_by', $user->id);

            $data['totalDocuments'] = (clone $documents)->count();

            $data['draftDocuments'] = (clone $documents)
                ->where('status', 'DRAFT')
                ->count();

            $data['submittedDocuments'] = (clone $documents)
                ->whereIn('status', [
                    'SUBMITTED',
                    'UNDER_REVIEW',
                ])
                ->count();

            $data['revisionDocuments'] = (clone $documents)
                ->where('status', 'REVISION_REQUIRED')
                ->count();

            $data['rejectedDocuments'] = (clone $documents)
                ->where('status', 'REJECTED')
                ->count();

            $data['finalApprovedDocuments'] = (clone $documents)
                ->where('status', 'FINAL_APPROVED')
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR / MANAGER
        |--------------------------------------------------------------------------
        */

        if (in_array($role, ['Supervisor', 'Manager'], true)) {
            $data['pendingApprovals'] = ApprovalStep::where(
                'approver_id',
                $user->id
            )
                ->where('status', 'PENDING')
                ->count();

            $data['approvedApprovals'] = ApprovalStep::where(
                'approver_id',
                $user->id
            )
                ->where('status', 'APPROVED')
                ->count();

            $data['rejectedApprovals'] = ApprovalStep::where(
                'approver_id',
                $user->id
            )
                ->where('status', 'REJECTED')
                ->count();

            $data['revisionApprovals'] = ApprovalStep::where(
                'approver_id',
                $user->id
            )
                ->where('status', 'REVISION_REQUIRED')
                ->count();

            if ($role === 'Manager') {
                $data['finalApprovedDocuments'] = Document::where(
                    'status',
                    'FINAL_APPROVED'
                )->count();
            }
        }

        return view('dashboard', $data);
    }
}
