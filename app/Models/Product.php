<?php

namespace App\Models;

use App\Enums\MediaCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Product extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'media',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
    ];

    /**
     * Indicates custom attributes to append to model.
     *
     * @var array
     */
    public $appends = [
        'featured_image',
    ];

     /**
     * Register media collections
     *
     * @return void
     */

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::FEATUREDIMAGE)
            // ->useFallbackUrl(url('/images/blog-post-placeholder.jpg'))
            ->singleFile();
    }

    /**
     * Gets full URL for post featured image.
     *
     * @return string
     */
    public function getFeaturedImageAttribute()
    {
        return $this->getFirstMediaUrl(MediaCollection::FEATUREDIMAGE);
    }

    /**
     * Get the artisan that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }

    /**
     * Get the category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the product's gallery.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function gallery()
    {
        return $this->morphMany(Gallery::class, 'gallerable');
    }
}
