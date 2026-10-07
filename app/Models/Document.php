<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'document_type_id',
        'created_by',
        'title',
        'description',
        'amount',
        'status',
        'submitted_at',
    ];

    /**
     * Tipe data untuk beberapa field.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    /**
     * Dokumen memiliki satu jenis dokumen.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Dokumen dibuat oleh satu user.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Dokumen memiliki banyak approval step.
     */
    public function approvalSteps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class);
    }

    /**
     * Dokumen memiliki banyak approval history.
     */
    public function approvalHistories(): HasMany
    {
        return $this->hasMany(ApprovalHistory::class);
    }

    /**
     * Dokumen memiliki banyak revision.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(DocumentRevision::class);
    }

    /**
     * Dokumen dapat memiliki banyak attachment.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(DocumentAttachment::class);
    }
}