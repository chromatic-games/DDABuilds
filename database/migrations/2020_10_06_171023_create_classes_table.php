<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('classes', function (Blueprint $table) {
			$table->unsignedInteger('id')->primary();
			$table->string('name');
			$table->unsignedTinyInteger('isHero');
			$table->unsignedTinyInteger('isDisabled')->default(0);
		});

		DB::table('classes')->insert([
			['id' => 1, 'name' => 'Squire', 'isHero' => 1, 'isDisabled' => 0],
			['id' => 2, 'name' => 'Apprentice', 'isHero' => 1, 'isDisabled' => 0],
			['id' => 3, 'name' => 'Huntress', 'isHero' => 1, 'isDisabled' => 0],
			['id' => 4, 'name' => 'Monk', 'isHero' => 1, 'isDisabled' => 0],
			['id' => 5, 'name' => 'Series EV-A', 'isHero' => 1, 'isDisabled' => 0],

			['id' => 20, 'name' => 'World', 'isHero' => 0, 'isDisabled' => 0],
			['id' => 21, 'name' => 'Hints', 'isHero' => 0, 'isDisabled' => 0],
			['id' => 22, 'name' => 'Arrows', 'isHero' => 0, 'isDisabled' => 0],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('classes');
	}
}
