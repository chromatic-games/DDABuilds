<?php

namespace Database\Seeders;

use App\Models\BugReport;
use App\Models\Build;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		Build::factory()->times(100)->create();
		BugReport::factory()->times(100)->create();
	}
}
