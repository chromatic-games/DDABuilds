<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateBuild extends Migration {
	public function up() {
		Schema::table('build', function (Blueprint $table) {
			$table->renameColumn('id', 'ID');
			$table->renameColumn('map', 'mapID');
			$table->renameColumn('name', 'title');
			$table->renameColumn('difficulty', 'difficultyID');
			$table->renameColumn('fk_buildstatus', 'buildStatus');
			$table->renameColumn('fk_user', 'steamID');
			$table->renameColumn('gamemodeID', 'gameModeID');
			$table->unsignedInteger('views')->nullable(false)->default(0)->change();
		});
		// use queries, stupid doctrine cant change int to tinyint -_-
		DB::statement('ALTER TABLE build CHANGE COLUMN deleted isDeleted TINYINT UNSIGNED NOT NULL DEFAULT 0;');
		DB::statement('ALTER TABLE build CHANGE COLUMN hardcore hardcore TINYINT UNSIGNED NOT NULL DEFAULT 0;');
		DB::statement('ALTER TABLE build CHANGE COLUMN afkable afkAble TINYINT UNSIGNED NOT NULL DEFAULT 0;');
	}

	public function down() {
		Schema::table('build', function (Blueprint $table) {
			$table->renameColumn('ID', 'id');
			$table->renameColumn('mapID', 'map');
			$table->renameColumn('title', 'name');
			$table->renameColumn('difficultyID', 'difficulty');
			$table->renameColumn('buildStatus', 'fk_buildstatus');
			$table->renameColumn('steamID', 'fk_user');
			$table->renameColumn('gameModeID', 'gamemodeID');
			$table->integer('hardcore')->nullable(true)->default('')->change();
			$table->integer('afkable')->nullable(true)->default('')->change();
			$table->integer('views')->change();
			$table->integer('deleted')->default(0)->change();
		});
	}
}