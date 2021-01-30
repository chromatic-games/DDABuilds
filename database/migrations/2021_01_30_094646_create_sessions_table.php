<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration {
	public function up() {
		Schema::create('session', function (Blueprint $table) {
			$table->string('id')->primary();
			$table->string('steam_id', 20)->nullable()->index();
			$table->text('payload');
			$table->integer('last_activity')->index();
		});
	}

	public function down() {
		Schema::dropIfExists('session');
	}
}
