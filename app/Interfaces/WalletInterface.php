<?php

namespace App\Interfaces;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface WalletInterface
{
    public function createWallet(): Wallet;
    public function chargeWallet(Model $model, $array);
    public function wallet(): MorphOne;
}
