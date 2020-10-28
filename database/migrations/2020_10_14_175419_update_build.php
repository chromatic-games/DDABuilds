<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateBuild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('build', function (Blueprint $table) {
	    	$table->renameColumn('map', 'map_id');
	    	$table->renameColumn('difficulty', 'difficulty_id');
	    	$table->renameColumn('fk_buildstatus', 'build_status_id');
	    	$table->renameColumn('fk_user', 'steam_id');
	    	$table->renameColumn('gamemodeID', 'game_mode_id');
	    	$table->unsignedInteger('views')->nullable(false)->default(0)->change();
	    });
	    // use queries, stupid doctrine cant change int to tinyint -_-
	    DB::statement('ALTER TABLE build CHANGE COLUMN deleted is_deleted TINYINT UNSIGNED NOT NULL DEFAULT 0;');
	    DB::statement('ALTER TABLE build CHANGE COLUMN hardcore hardcore TINYINT UNSIGNED NOT NULL DEFAULT 0;');
	    DB::statement('ALTER TABLE build CHANGE COLUMN afkable afk_able TINYINT UNSIGNED NOT NULL DEFAULT 0;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('build', function (Blueprint $table) {
		    $table->renameColumn('map_id', 'map');
		    $table->renameColumn('difficulty_id', 'difficulty');
		    $table->renameColumn('build_status_id', 'fk_buildstatus');
	    	$table->renameColumn('steam_id', 'fk_user');
	    	$table->renameColumn('game_mode_id', 'gamemodeID');
		    $table->integer('hardcore')->nullable(true)->default('')->change();
		    $table->integer('afkable')->nullable(true)->default('')->change();
		    $table->integer('views')->change();
		    $table->integer('deleted')->default(0)->change();
	    });
    }
}