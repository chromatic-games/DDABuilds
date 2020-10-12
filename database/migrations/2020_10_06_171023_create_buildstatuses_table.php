<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBuildstatusesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('buildstatuses', function (Blueprint $table) {
			$table->unsignedInteger('id')->primary();
			$table->string('name');
		});

		DB::table('buildstatuses')->insert([
			['id' => 1, 'name' => 'Public',],
			['id' => 2, 'name' => 'Unlisted',],
			['id' => 3, 'name' => 'Private',],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('buildstatuses');
	}
}
