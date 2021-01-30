<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRiftedColumn extends Migration {
	public function up() {
		Schema::table('build', function (Blueprint $table) {
			$table->unsignedTinyInteger('rifted')->default(0)->after('afkAble');
		});

		$replaceStrings = [
			"%, rifted",
			"% rift mode",
			"% (rifted)",
			"% | rifted",
			"rifted |%",
			"rifted %",
			"rift %",
			"% rifted",
			"%- rifted survival - %",
			"%rift %",
			"%rifted %",
		];

		foreach ( $replaceStrings as $str ) {
			DB::update('UPDATE build SET rifted = 1, title = REPLACE(title, "'.str_replace('%', '', $str).'", "") WHERE title LIKE "'.$str.'";');
		}
	}

	public function down() {
		Schema::table('build', function (Blueprint $table) {
			$table->dropColumn('rifted');
		});
	}
}
