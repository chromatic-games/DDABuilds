<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMapsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('maps', function (Blueprint $table) {
			$table->unsignedInteger('id')->primary();
			$table->string('name');
			$table->unsignedInteger('units');
			$table->unsignedInteger('sort');
			$table->unsignedInteger('fk_mapcategory');
		});

		DB::table('maps')->insert([
			['id' => 1, 'name' => 'The Deeper Well', 'units' => 60, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 2, 'name' => 'Ancient Mines', 'units' => 80, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 3, 'name' => 'Lava Mines', 'units' => 60, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 4, 'name' => 'Alchemical Laboratory', 'units' => 85, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 5, 'name' => 'Tornado Valley', 'units' => 85, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 6, 'name' => 'Tornado Highlands', 'units' => 90, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 7, 'name' => 'The Ramparts', 'units' => 100, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 8, 'name' => 'The Throne Room', 'units' => 100, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 9, 'name' => 'Arcane Library', 'units' => 110, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 10, 'name' => 'Royal Gardens', 'units' => 130, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 11, 'name' => 'The Promenade', 'units' => 140, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 12, 'name' => 'The Summit', 'units' => 150, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 13, 'name' => 'Magus Quarters', 'units' => 90, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 14, 'name' => 'Endless Spires', 'units' => 110, 'sort' => 0, 'fk_mapcategory' => 1],
			['id' => 15, 'name' => 'Glitterhelm Caverns', 'units' => 165, 'sort' => 0, 'fk_mapcategory' => 1],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('maps');
	}
}
