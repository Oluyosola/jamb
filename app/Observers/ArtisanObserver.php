<?php

namespace App\Observers;

use App\Actions\GenerateUniqueIdAction;
use App\Models\Artisan;
Use App\Jobs\CoordinateByAddress;
use App\Notifications\User\WalletCreatedNotification;
use Illuminate\Support\Facades\Notification;

class ArtisanObserver
{
    /**
     * Handle the Artisan "creating" event.
     *
     * @param  \App\Models\Artisan  $artisan
     * @return void
     */
    public function creating(Artisan $artisan)
    {
        $artisan->unique_id = 'JA' . (new GenerateUniqueIdAction())->execute($artisan->getTable(), 'unique_id');
    }

    /**
     * Handle the Artisan "created" event.
     *
     * @param  \App\Models\Artisan  $artisan
     * @return void
     */
    public function created(Artisan $artisan)
    {
        $wallet = $artisan->createWallet();

        // Notify Artisan
        if (!is_null($artisan->email)) {
            Notification::route('mail', $artisan->email)->notify(new WalletCreatedNotification($wallet));
        }

        CoordinateByAddress::dispatch($artisan);
    }

    /**
     * Handle the Artisan "updated" event.
     *
     * @param  \App\Models\Artisan  $artisan
     * @return void
     */
    public function updated(Artisan $artisan)
    {
        CoordinateByAddress::dispatch($artisan);
    }


    /**
     * Handle the Artisan "deleted" event.
     *
     * @param  \App\Models\Artisan  $artisan
     * @return void
     */
    public function deleted(Artisan $artisan)
    {
        //
    }

    /**
     * Handle the Artisan "restored" event.
     *
     * @param  \App\Models\Artisan  $artisan
     * @return void
     */
    public function restored(Artisan $artisan)
    {
        //
    }

    /**
     * Handle the Artisan "force deleted" event.
     *
     * @param  \App\Models\Artisan  $artisan
     * @return void
     */
    public function forceDeleted(Artisan $artisan)
    {
        //
    }
}
