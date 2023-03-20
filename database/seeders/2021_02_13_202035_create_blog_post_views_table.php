<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogPostViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("post_id");
            $table->string("title_slug")->nullable();
            $table->string("url")->nullable();
            $table->string("session_id")->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string("ip")->nullable();
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
        Schema::dropIfExists('blog_post_views');
    }
}
