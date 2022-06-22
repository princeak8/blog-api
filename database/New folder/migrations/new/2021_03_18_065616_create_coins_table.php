<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('abbrv');
            $table->string('logo');
            $table->float('highest_price');
            $table->float('lowest_price');
            $table->float('market_cap');
            $table->float('fully_diluted_cap');
            $table->integer('circulating_supply');
            $table->integer('max_supply');
            $table->string('founded');
            $table->string('founded_by');
            $table->text('about');
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
        Schema::dropIfExists('coins');
    }
}
