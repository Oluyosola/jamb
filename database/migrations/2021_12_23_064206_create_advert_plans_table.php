<?php

use App\Models\AdvertType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('standard_price');
            $table->foreignIdFor(AdvertType::class)
                ->nullable()
                ->constrained()
                ->comment('The type of advert');
            $table->integer('duration_in_days');
            $table->integer('advert_type_weight')
                ->nullable()
                ->comment('Should be nulable if advert type is a fixed duration and
                    not for pay per view, then specify number of views.');
            $table->string('description')->nullable();
            $table->boolean('publish')->default(true);
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
        Schema::dropIfExists('advert_plans');
    }
}
