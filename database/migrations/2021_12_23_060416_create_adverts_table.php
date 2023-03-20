<?php

use App\Models\AdvertPlan;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(AdvertPlan::class);
            $table->foreignId('transaction_id')->nullable()->constrained();
            $table->boolean('is_recurring')->default(true);
            $table->decimal('charging_price', 10, 2)->nullable();
            $table->string('charging_currency')->nullable();
            $table->boolean('is_paused')->default(false);
            $table->boolean('is_terminated')->default(false);
            $table->longText('termination_reason')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->bigInteger('view_count')->nullable();
            $table->longText('advert_url')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
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
        Schema::dropIfExists('adverts');
    }
}
