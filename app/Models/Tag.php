<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug'
    ];

    /**
     * Связь Многие-ко-многим между Tag и Receipt (через таблицу receipt_tag)
     * @return BelongsToMany
     */
    public function receipts(): BelongsToMany
    {
        return $this->belongsToMany(Receipt::class);
    }
}
