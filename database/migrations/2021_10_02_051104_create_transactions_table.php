<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('transactionable');
            $table->string('reference');
            $table->foreignIdFor(User::class)->constrained();
            $table->string('payment_status');
            $table->string('payment_gateway')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_purpose')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->string('currency')->nullable();
            $table->string('amount')->nullable();
            $table->float('discount')->nullable()->comment('This column is in percentage');
            $table->longText('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
