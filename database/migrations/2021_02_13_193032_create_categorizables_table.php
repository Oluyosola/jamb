<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorizablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorizables', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->morphs('categorizable');
            $table->timestamps();

            $table->unique(
                ['category_id', 'categorizable_id', 'categorizable_type'],
                'cat_ables_cat_id_catable_id_catable_type'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorizables');
    }
}
