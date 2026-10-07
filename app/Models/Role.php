<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Satu role dapat dimiliki oleh banyak user.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}