<?php

namespace App\Services;

use App\Enums\PaymentPurpose;
use App\Models\Artisan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class UserService
{

     /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return User::query();
    }

    /**
     * Check if user has paid callout charge
     *
     * @param User $user
     * @param $location
     * @return bool
     */
    public function hasPaidLocationCallOutCharge(User $user, $location)
    {
        $withinTwentyFourHours = Carbon::now()->subHours(24)->toDateTimeString();

        $result = $user->transactions()
            ->where('created_at', '>=', $withinTwentyFourHours)
            ->where('payment_purpose', PaymentPurpose::CALLOUTCHARGE)
            ->whereHasMorph(
                'transactionable',
                [Artisan::class],
                function (Builder $query) use ($location) {
                    $query->whereHas('state', fn ($q) => $q->where('name', 'like', "%{$location}%"))
                        ->orWhereHas('city', fn ($q) => $q->where('name', 'like', "%{$location}%"))
                        ->orWhere('address', 'like', "%$location%");
                }
            )
            ->exists();

        return $result;
    }

    /**
     * Delete an user.
     *
     * @param $user
     * @return bool|null
     */
    public function destroy($user)
    {
        return $user->delete();
    }
}
