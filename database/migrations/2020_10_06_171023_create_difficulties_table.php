<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDifficultiesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('difficulties', function (Blueprint $table) {
			$table->unsignedInteger('id')->primary();
			$table->string('name', 30);
		});

		DB::table('difficulties')->insert([
			['id' => 1, 'name' => 'Easy',],
			['id' => 2, 'name' => 'Medium',],
			['id' => 3, 'name' => 'Hard',],
			['id' => 4, 'name' => 'Insane',],
			['id' => 5, 'name' => 'Nightmare',],
			['id' => 6, 'name' => 'Massacre',],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('difficulties');
	}
}
