<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMediaModel;

class Media extends BaseMediaModel
{
    /**
     * Indicates custom attributes to append to model.
     *
     * @var array
     */
    public $appends = ['full_url'];

    /**
     * Gets full URL of the media
     *
     * @return string
     */
    public function getFullUrlAttribute()
    {
        return $this->getFullUrl();
    }
}
