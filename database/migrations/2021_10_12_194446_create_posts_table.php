<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('postable');
            $table->string('post_type');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->longText('body');
            $table->longText('meta')->nullable();
            $table->boolean('is_featured')->default(0);
            $table->timestamp('featured_at')->nullable();
            $table->boolean('is_published')->default(0)->comment('Toggled by an Admin.');
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_approved')->default(0)->comment('Toggled by Admin.');
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_active')->comment('Is post active? Toggled by user!!!');
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
        Schema::dropIfExists('posts');
    }
}
