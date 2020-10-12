<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBuildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->foreign('gamemodeID', 'builds_ibfk_1')->references('gamemodeID')->on('gamemode')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('map', 'builds_ibfk_2')->references('id')->on('maps')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('difficulty', 'builds_ibfk_3')->references('id')->on('difficulties')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('fk_buildstatus', 'builds_ibfk_4')->references('id')->on('buildstatuses')->onUpdate('CASCADE')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->dropForeign('builds_ibfk_1');
            $table->dropForeign('builds_ibfk_2');
            $table->dropForeign('builds_ibfk_3');
            $table->dropForeign('builds_ibfk_4');
        });
    }
}
