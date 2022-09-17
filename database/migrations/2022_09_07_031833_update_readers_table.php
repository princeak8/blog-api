<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('readers', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('provider_name')->nullable()->after('password');
            $table->string('provider_id')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('readers', function (Blueprint $table) {
            $table->dropColumn('provider_id');
            $table->dropColumn('provider_name');
            $table->string('password')->change();
        });
    }
}
