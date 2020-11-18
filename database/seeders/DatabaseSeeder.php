<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		$this->call(SteamUserSeeder::class);
		$this->call(BuildSeeder::class);
		$this->call(BugReportSeeder::class);
	}
}
