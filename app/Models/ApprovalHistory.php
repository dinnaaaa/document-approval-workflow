<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'approval_step_id',
        'user_id',
        'action',
        'comment',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function approvalStep(): BelongsTo
    {
        return $this->belongsTo(ApprovalStep::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}