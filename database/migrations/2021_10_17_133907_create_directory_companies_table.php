<?php

use App\Models\Admin;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->morphs('creator');
            $table->foreignIdFor(Category::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(SubCategory::class)->constrained()->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('year_founded')->nullable();
            $table->text('description')->nullable();
            $table->boolean('publish')->default(false);
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
        Schema::dropIfExists('directory_companies');
    }
}
