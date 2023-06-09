<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->index();
            $table->unsignedBigInteger('visitable_id');
            $table->string('visitable_type');
            $table->date('date');
            $table->timestamps();

            $table->unique(['ip', 'visitable_id', 'visitable_type', 'date']);
            $table->index(['visitable_id', 'visitable_type', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex(['visitable_id', 'visitable_type', 'date']);
        });
        Schema::dropIfExists('visits');
    }
}
