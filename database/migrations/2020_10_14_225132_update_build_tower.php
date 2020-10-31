<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBuildTower extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('build_tower', function (Blueprint $table) {
		    $table->renameColumn('fk_build', 'buildID');
		    $table->renameColumn('fk_tower', 'towerID');
		    $table->renameColumn('fk_buildwave', 'buildWaveID');
		    $table->renameColumn('override_du', 'overrideUnits');
	    });
	    Schema::table('build_tower', function (Blueprint $table) {
		    $table->unsignedInteger('x')->change();
		    $table->unsignedInteger('y')->change();
		    $table->unsignedInteger('rotation')->change();
		    $table->unsignedInteger('overrideUnits')->change();
		    $table->unsignedInteger('buildWaveID')->change();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('build_tower', function (Blueprint $table) {
		    $table->integer('x')->change();
		    $table->integer('y')->change();
		    $table->integer('rotation')->change();
		    $table->integer('overrideUnits')->change();
		    $table->integer('buildWaveID')->change();
	    });
	    Schema::table('build_tower', function (Blueprint $table) {
		    $table->renameColumn('buildID', 'fk_build');
		    $table->renameColumn('towerID', 'fk_tower');
		    $table->renameColumn('buildWaveID', 'fk_buildwave');
		    $table->renameColumn('overrideUnits', 'override_du');
	    });
    }
}