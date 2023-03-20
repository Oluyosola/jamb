<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedAccountMessage extends Model
{
    use HasFactory;

    /**
    * Get the owning model.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphTo
    */
    public function model()
    {
        return $this->morphTo();
    }
}
