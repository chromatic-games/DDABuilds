<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMapAvailableUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('map_available_unit', function (Blueprint $table) {
		    $table->renameColumn('mapID', 'map_id');
		    $table->renameColumn('difficultyID', 'difficulty_id');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('map_available_unit', function (Blueprint $table) {
		    $table->renameColumn('map_id', 'mapID');
		    $table->renameColumn('difficulty_id', 'difficultyID');
	    });
    }
}