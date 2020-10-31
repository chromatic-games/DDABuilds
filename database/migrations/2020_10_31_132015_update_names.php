<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateNames extends Migration {
	public $tables = [
		'map',
		'difficulty',
		'game_mode',
		'map_category',
		'hero',
		'tower',
	];

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		foreach ( $this->tables as $table ) {
			echo 'updating '.$table.' name column'.PHP_EOL;
			foreach ( DB::table($table)->select()->get() as $item ) {
				DB::table($table)->where('ID', $item->ID)->update([
					'name' => lcfirst(str_replace([' ', '-'], '', $item->name)),
				]);
			}
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
	}
}
