<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBuildwavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildwaves', function (Blueprint $table) {
            $table->foreign('fk_build', 'buildwaves_ibfk_1')->references('id')->on('builds')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildwaves', function (Blueprint $table) {
            $table->dropForeign('buildwaves_ibfk_1');
        });
    }
}
