<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectoryCompanySocial extends Model
{
    use HasFactory;

    /**
     * Scope a query to only include published posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query, $value = true)
    {
        return $query->where('publish', $value);
    }

    /**
     * Get the directoryCompany.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function directoryCompany()
    {
        return $this->belongsTo(DirectoryCompany::class);
    }
}
