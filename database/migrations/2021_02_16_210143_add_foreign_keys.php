<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration {
	public $keys = [
		'bug_report' => [
			'steamID' => 'steam_user.ID',
		],
		'bug_report_comment' => [
			'steamID' => 'steam_user.ID',
			'bugReportID' => 'bug_report.reportID',
		],
		'build' => [
			'steamID' => 'steam_user.ID',
			'mapID' => 'map.ID',
			'difficultyID' => 'difficulty.ID',
			'gameModeID' => 'game_mode.ID',
		],
		'build_comment' => [
			'steamID' => 'steam_user.ID',
			'buildID' => 'build.ID',
		],
		'build_stats' => [
			'buildID' => 'build.ID',
			'heroID' => 'hero.ID',
		],
		'build_tower' => [
			'towerID' => 'tower.ID',
			'buildWaveID' => 'build_wave.waveID',
		],
		'build_watch' => [
			'steamID' => 'steam_user.ID',
			'buildID' => 'build.ID',
		],
		'build_wave' => [
			'buildID' => 'build.ID',
		],
		'like' => [
			'steamID' => 'steam_user.ID',
		],
		'map' => [
			'mapCategoryID' => 'map_category.ID',
		],
		'map_available_unit' => [
			'mapID' => 'map.ID',
			'difficultyID' => 'difficulty.ID',
		],
		'tower' => [
			'heroClassID' => 'hero.ID',
		],
	];

	public function up() {
		// add steam ids
		foreach ( $this->keys as $tableName => $keys ) {
			Schema::table($tableName, function (Blueprint $table) use ($tableName, $keys) {
				$i = 1;
				foreach ( $keys as $column => $reference ) {
					$split = explode('.', $reference);
					$table->foreign($column, $tableName.'_ibfk_'.$i)->references($split[1])->on($split[0])->onUpdate('CASCADE')->onDelete('CASCADE');
					$i++;
				}
			});
		}
	}

	public function down() {
	}
}
