<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalStep extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'document_id',
        'step_order',
        'approver_id',
        'role',
        'status',
        'acted_at',
        'comment',
    ];

    /**
     * Tipe data untuk waktu tindakan approval.
     */
    protected $casts = [
        'acted_at' => 'datetime',
    ];

    /**
     * Approval step milik satu dokumen.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Approval step ditugaskan kepada satu user.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
