<?php

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->morphs('creator');
            $table->string('title');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('venue_type');
            $table->string('venue_details')->nullable();
            $table->foreignIdFor(Country::class)->nullable()->nullOnDelete();
            $table->foreignIdFor(State::class)->nullable()->nullOnDelete();
            $table->foreignIdFor(City::class)->nullable()->nullOnDelete();
            $table->string('address')->nullable();
            $table->text('registration_details')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_published')->default(true);
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
        Schema::dropIfExists('events');
    }
}
