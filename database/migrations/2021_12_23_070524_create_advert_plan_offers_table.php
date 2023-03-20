<?php

use App\Models\AdvertOffer;
use App\Models\AdvertPlan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertPlanOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_plan_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AdvertPlan::class)->constrained();
            $table->foreignIdFor(AdvertOffer::class)->constrained();
            $table->string('description')->nullable();
            $table->boolean('publish')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_plan_offers');
    }
}
