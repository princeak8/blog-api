<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 50);
            $table->string('profile_name', 50);
            $table->string('password', 255);
            $table->string('name', 50);
            $table->string('role', 50);
            $table->text('about');
            $table->string('subscribe_msg');
            $table->tinyinteger('accesslevel');
            $table->tinyinteger('blocked');
            $table->string('block_reason');
            $table->string('last_seen', 255);
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
        Schema::dropIfExists('users');
    }
}
