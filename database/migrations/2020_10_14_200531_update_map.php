<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMap extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('map', function (Blueprint $table) {
			$table->renameColumn('id', 'ID');
		});
		Schema::table('map', function (Blueprint $table) {
			$table->increments('ID')->change();
			$table->renameColumn('fk_mapcategory', 'mapCategoryID');
			$table->dropColumn('sort');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('map', function (Blueprint $table) {
			$table->renameColumn('ID', 'id');
		});
		Schema::table('map', function (Blueprint $table) {
			$table->renameColumn('mapCategoryID', 'fk_mapcategory');
			$table->unsignedInteger('id')->change();
			$table->unsignedInteger('sort')->after('units');
		});
	}
}