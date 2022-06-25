<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('file_type');
            $table->string('mime_type');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('extension');
            $table->mediumInteger('size');
            $table->string('formatted_size');
            $table->string('url');
            $table->string('secure_url')->nullable();
            $table->string('public_id')->nullable();
            $table->mediumInteger('width')->nullable();
            $table->mediumInteger('height')->nullable();
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
        Schema::dropIfExists('files');
    }
}
