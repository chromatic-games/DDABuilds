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
			$table->renameColumn('id', 'waveID');
			$table->renameColumn('fk_build', 'buildID');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('build_wave', function (Blueprint $table) {
			$table->unsignedInteger('ID')->after('name')->change();
		});
		Schema::table('build_wave', function (Blueprint $table) {
			$table->renameColumn('waveID', 'id');
			$table->renameColumn('buildID', 'fk_build');
		});
	}
}