<?php

namespace App\Models;

use App\Enums\MediaCollection;
use App\Models\User;
use App\Traits\Visitable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;
    use Visitable;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Register media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::FEATUREDIMAGE)
            ->useFallbackUrl(url('/images/blog-post-placeholder.jpg'))
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
     * Scope a query to only include published posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include approved posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Get the parent creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Morph;
     */
    public function postable()
    {
        return $this->morphTo('postable');
    }

    /**
     * Get all the categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable')->withTimestamps();
    }

    /**
     * Get the category that this blog post belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that create a specific blog's post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the comments under the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('parent_id', null);
    }

    /**
     * Get the comments under the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function approvedComments()
    {
        return $this->morphMany(Comment::class, 'commentable')->approved();
    }

    /**
     * The tags that are associated with the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the post's likes.
     *
     * @return MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Get all the post views.
     *
     * @return mixed
     */
    public function getViewCountAttribute()
    {
        return $this->visitsForever();
    }

    /**
       * Get all of the post's photos.
       * @return \Illuminate\Database\Eloquent\Relations\HasMany
       */
    public function blogPostPhotos()
    {
        return $this->hasMany(BlogPostPhoto::class, 'post_id', 'id');
    }
}
