<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGameMode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('game_mode', function (Blueprint $table) {
		    $table->renameColumn('gamemodeID', 'id');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('game_mode', function (Blueprint $table) {
		    $table->renameColumn('id', 'gamemodeID');
	    });
    }
}