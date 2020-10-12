<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGamemodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gamemode', function (Blueprint $table) {
            $table->increments('gamemodeID');
            $table->string('name', 64);
        });

	    DB::table('gamemode')->insert([
		    ['gamemodeID' => 1, 'name' => 'Campaign',],
		    ['gamemodeID' => 2, 'name' => 'Survival',],
		    ['gamemodeID' => 3, 'name' => 'Challenge',],
		    ['gamemodeID' => 4, 'name' => 'Pure Strategy',],
		    ['gamemodeID' => 5, 'name' => 'Mix Mode',],
	    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gamemode');
    }
}
