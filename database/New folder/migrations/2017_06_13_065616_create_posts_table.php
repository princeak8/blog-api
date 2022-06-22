<?php

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
            $table->increments('id');
            $table->string('title', 255);
            $table->string('preview', 255);
            $table->string('cover_photo', 255)->nullable();
            $table->text('post', 255);
            $table->integer('category_id');
            $table->integer('user_id');
            $table->tinyinteger('published')->default('0');
            $table->tinyinteger('visible')->default('1');
            $table->biginteger('views');
            $table->string('published_at', 255);
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
        Schema::dropIfExists('posts');
    }
}
