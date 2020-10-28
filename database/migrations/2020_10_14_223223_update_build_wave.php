<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBuildWave extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('build_wave', function (Blueprint $table) {
			$table->unsignedInteger('fk_build')->after('id')->change();
		});
		Schema::table('build_wave', function (Blueprint $table) {
			$table->renameColumn('id', 'wave_id');
			$table->renameColumn('fk_build', 'build_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('build_wave', function (Blueprint $table) {
			$table->unsignedInteger('build_id')->after('name')->change();
		});
		Schema::table('build_wave', function (Blueprint $table) {
			$table->renameColumn('wave_id', 'id');
			$table->renameColumn('build_id', 'fk_build');
		});
	}
}