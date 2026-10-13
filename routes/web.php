<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Document, Approval & Notification
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    Route::get('/documents', [DocumentController::class, 'index'])
        ->name('documents.index');

    Route::get('/documents/create', [DocumentController::class, 'create'])
        ->name('documents.create');

    Route::post('/documents', [DocumentController::class, 'store'])
        ->name('documents.store');

    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])
        ->name('documents.edit');

    Route::put('/documents/{document}', [DocumentController::class, 'update'])
        ->name('documents.update');

    Route::post('/documents/{document}/submit', [DocumentController::class, 'submit'])
        ->name('documents.submit');

    /*
    |--------------------------------------------------------------------------
    | Document Attachments
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/documents/{document}/attachments',
        [DocumentController::class, 'uploadAttachment']
    )->name('documents.attachments.upload');

    Route::get(
        '/documents/{document}/attachments/{attachment}/download',
        [DocumentController::class, 'downloadAttachment']
    )->name('documents.attachments.download');

    Route::delete(
        '/documents/{document}/attachments/{attachment}',
        [DocumentController::class, 'deleteAttachment']
    )->name('documents.attachments.delete');

    Route::get('/documents/{document}', [DocumentController::class, 'show'])
        ->name('documents.show');

    /*
    |--------------------------------------------------------------------------
    | Approvals
    |--------------------------------------------------------------------------
    */

    Route::get('/approvals', [ApprovalController::class, 'index'])
        ->middleware('role:Supervisor,Manager')
        ->name('approvals.index');

    Route::get('/approvals/{approvalStep}', [ApprovalController::class, 'show'])
        ->middleware('role:Supervisor,Manager')
        ->name('approvals.show');

    Route::post('/approvals/{approvalStep}/process', [ApprovalController::class, 'process'])
        ->middleware('role:Supervisor,Manager')
        ->name('approvals.process');

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';