<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTables extends Migration {
	private $tables = [
		'builds'        => 'build',
		'maps'          => 'map',
		'difficulties'  => 'difficulty',
		'buildstatuses' => 'build_status',
		'buildwaves'    => 'build_wave',
		'classes'       => 'hero',
		'towers'        => 'tower',
		'placed'        => 'build_tower',
		'comments'      => 'build_comment',
		'gamemode'      => 'game_mode',
		'mapcategories' => 'map_category',
		'notifications' => 'notification',
	];

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		foreach ( $this->tables as $from => $to ) {
			Schema::table($from, function (Blueprint $table) use ($to) {
				$table->rename($to);
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		foreach ( $this->tables as $to => $from ) {
			Schema::table($from, function (Blueprint $table) use ($to) {
				$table->rename($to);
			});
		}
	}
}