<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHero extends Migration {
	public function up() {
		Schema::table('hero', function (Blueprint $table) {
			$table->renameColumn('id', 'ID');
		});
	}

	public function down() {
		Schema::table('hero', function (Blueprint $table) {
			$table->renameColumn('ID', 'id');
		});
	}
}
