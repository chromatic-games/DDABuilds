<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLocaleTable extends Migration {
	public function up() {
		Schema::create('locale', function (Blueprint $table) {
			$table->increments('ID');
			$table->string('languageCode');
			$table->string('name');
		});

		DB::table('locale')->insert([
			[
				'languageCode' => 'en',
				'name'         => 'English',
			],
			[
				'languageCode' => 'de',
				'name'         => 'Deutsch',
			],
		]);
	}

	public function down() {
		Schema::dropIfExists('locale');
	}
}
