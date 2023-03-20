<?php

namespace App\Models;

use App\Enums\MediaCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['media'];

    /**
     * Get the owning gallery-able model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function gallerable()
    {
        return $this->morphTo();
    }

    /**
     * Registers media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::GALLERY);
    }
}
