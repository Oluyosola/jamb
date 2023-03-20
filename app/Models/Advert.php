<?php

namespace App\Models;

use App\Enums\MediaCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Advert extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    /**
     * Indicates custom attributes to append to model.
     *
     * @var array
     */
    public $appends = ['advert_banner'];

    /**
     * Gets full URL for organization logo
     *
     * @return string
     */
    public function getAdvertBannerAttribute()
    {
        return $this->getFirstMediaUrl(MediaCollection::ADVERTBANNER);
    }

    /**
     * Get if the advert subscription has expired.
     *
     * @return bool
     */
    public function getIsExpiredAttribute()
    {
        return $this->is_paused
            ? false
            : $this->end_date <= now()->toDateTimeString();
    }

    /** Scope a query to only include terminated records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTerminated($query, $value = true)
    {
        return $query->where('is_terminated', $value);
    }

    /** Scope a query to only include paused records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaused($query, $value = true)
    {
        return $query->where('is_paused', $value);
    }

     /**
     * Registers media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::ADVERTBANNER)
            ->singleFile();
    }

    /**
     * Get the owning user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the advert transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    /**
     * Get the advert plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advertPlan()
    {
        return $this->belongsTo(AdvertPlan::class);
    }
}
