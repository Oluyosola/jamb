<?php

namespace App\Models;

use App\Enums\MediaCollection;
use App\Interfaces\WalletInterface;
use App\Notifications\User\ResetPassword;
use App\Notifications\User\VerifyEmail;
use App\Traits\WalletTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia, WalletInterface
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use InteractsWithMedia;
    use WalletTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $appends = [
        'profile_picture',
        'wallet_balance',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Gets full URL for post profile picture.
     *
     * @return string
     */
    public function getProfilePictureAttribute()
    {
        return $this->getFirstMediaUrl(MediaCollection::PROFILEPICTURE);
    }

    /**
     * Gets full URL for post profile picture.
     *
     * @return string
     */
    public function getWalletBalanceAttribute()
    {
        return $this->wallet?->balance;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $callbackUrl = request('callbackUrl', config('frontend.user.url'));

        $this->notify(new VerifyEmail($callbackUrl));
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $callbackUrl = request('callbackUrl', config('frontend.user.url'));

        $this->notify(new ResetPassword($callbackUrl, $token));
    }

     /**
     * Registers media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::PROFILEPICTURE)
            ->useFallbackUrl(url('/images/profile-picture-placeholder.jpg'))
            ->singleFile();
    }

    /**
     * Get the country the user model belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
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
     * Get the user's likes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the user's posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function posts()
    {
        return $this->morphMany(Post::class, 'postable');
    }

    /**
     * Get the user's advert subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function advertSubscriptions()
    {
        return $this->hasMany(Advert::class);
    }

    /**
     * Get the user's created events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->morphMany(Event::class, 'creator');
    }

    /**
     * Get the user's "active/running" advert.
     *
     * @return Advert
     */
    public function activeAdvertSubscriptions()
    {
        return $this->advertSubscriptions()
            ->terminated(false)
            ->paused(false)
            ->where('end_date', '>=', now()->toDateTimeString())
            ->latest();
    }

    /**
     * Get the user's bank detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function bankDetail()
    {
        return $this->morphOne(BankDetail::class, 'owner');
    }

    /**
     * Get the user's wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function wallet(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Wallet::class, 'owner');
    }

    /**
     * Get the user's payment transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the user's cart items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the user's orders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
