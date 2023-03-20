<?php

namespace App\Models;

use App\Enums\MediaCollection;
use App\Traits\BlockedAccountMessageTrait;
use App\Traits\WalletTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Artisan extends Model implements HasMedia
{
    use BlockedAccountMessageTrait;
    use HasFactory;
    use InteractsWithMedia;
    use Notifiable;
    use Searchable;
    use SoftDeletes;
    use WalletTrait;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'media',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_blocked' => 'boolean',
    ];

    /**
     * Indicates custom attributes to append to model.
     *
     * @var array
     */
    public $appends = [
        'logo',
    ];

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return $this->blocked(false) && $this->active(true);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->only([
            'id',
            'business_name',
            // 'email',
            // 'phone',
            'address',
            'profile',
            'city_id',
            'state_id',
            // 'category'
        ]);
        // $array['logo'] = $this->getLogoAttribute();
        // $array['full_name'] = $this->getFullNameAttribute();
        if (config('scout.driver') !== 'database') {
            $array['city'] = $this->city?->name;
            $array['state'] = $this->state?->name;
            $array['category'] = $this->category?->name;
        }

        return $array;
    }

    /**
     * Register media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::LOGO)
            ->useFallbackUrl(url('/images/blog-post-placeholder.jpg'))
            ->singleFile();
    }

    /**
     * Gets full URL of the logo.
     *
     * @return string
     */
    public function getLogoAttribute()
    {
        return $this->getFirstMediaUrl(MediaCollection::LOGO);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /** Scope a query to only include active records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query, $value = true)
    {
        return $query->where('is_active', $value);
    }

    /** Scope a query to only include unblocked records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBlocked($query, $value = true)
    {
        return $query->where('is_blocked', $value);
    }

    /**
     * Get the artisan's bank detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function bankDetail()
    {
        return $this->morphOne(BankDetail::class, 'owner');
    }

    /**
     * Get the artisan's wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'owner');
    }

    /**
     * Get the state the model belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    /**
     * Get the city the model belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    /**
     * Get the blocked account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function blockedAccountMessages()
    {
        return $this->morphMany(BlockedAccountMessage::class, 'model');
    }

    /**
     * Get the model's association.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function association()
    {
        return $this->belongsTo(Association::class);
    }

     /**
     * Get the model's category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(City::class);
    }

}
