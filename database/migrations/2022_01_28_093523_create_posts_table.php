<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('cover_photo')->nullable();
            $table->text('content', 255);
            $table->integer('category_id')->nullable();
            $table->foreignId('user_id');
            $table->tinyinteger('published')->default(0);
            $table->tinyinteger('visible')->default(1);
            $table->biginteger('views')->default(0);
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
