<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMapAvailableUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_available_unit', function (Blueprint $table) {
            $table->unsignedInteger('mapID');
            $table->unsignedInteger('difficultyID');
            $table->unsignedSmallInteger('units');
            $table->primary(['mapID', 'difficultyID', 'units']);
        });
	    DB::table('map_available_unit')->insert([
		    ['mapID' => 3, 'difficultyID' => 4, 'units' => 90],
		    ['mapID' => 3, 'difficultyID' => 5, 'units' => 90],
		    ['mapID' => 3, 'difficultyID' => 6, 'units' => 90],
	    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('map_available_unit');
    }
}
