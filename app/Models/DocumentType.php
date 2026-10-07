<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * Satu jenis dokumen dapat memiliki banyak dokumen.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}