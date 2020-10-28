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
		    $table->renameColumn('fk_build', 'build_id');
		    $table->renameColumn('fk_tower', 'tower_id');
		    $table->renameColumn('fk_buildwave', 'build_wave_id');
		    $table->renameColumn('override_du', 'override_units');
	    });
	    Schema::table('build_tower', function (Blueprint $table) {
		    $table->unsignedInteger('x')->change();
		    $table->unsignedInteger('y')->change();
		    $table->unsignedInteger('rotation')->change();
		    $table->unsignedInteger('override_units')->change();
		    $table->unsignedInteger('build_wave_id')->change();
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
		    $table->integer('override_du')->change();
		    $table->integer('build_wave_id')->change();
	    });
	    Schema::table('build_tower', function (Blueprint $table) {
		    $table->renameColumn('build_id', 'fk_build');
		    $table->renameColumn('tower_id', 'fk_tower');
		    $table->renameColumn('build_wave_id', 'fk_buildwave');
		    $table->renameColumn('override_units', 'override_du');
	    });
    }
}