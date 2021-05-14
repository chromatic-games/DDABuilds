<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateTowerResizeableColumn extends Migration {
	public function up() {
		Schema::table('tower', function (Blueprint $table) {
			$table->unsignedTinyInteger('isResizable')->after('name');
			$table->unsignedTinyInteger('isRotatable')->after('isResizable');
		});

		// set resizeable
		DB::update('UPDATE tower SET isResizable = 1 WHERE heroClassID = 5');

		// set rotatable
		DB::update('UPDATE tower SET isRotatable = 1 WHERE heroClassID <> 3 AND heroClassID <> 4');
	}

	public function down() {
		Schema::table('tower', function (Blueprint $table) {
			$table->removeColumn('isResizable');
			$table->removeColumn('isRotatable');
		});
	}
}
