<?php

namespace Database\Seeders;

use App\Models\BugReport;
use App\Models\Build;
use App\Models\SteamUser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		SteamUser::factory()->createMany([
			[
				'steamID' => 76561198054589426,
				'name' => 'derpierre65',
				'avatarHash' => 'ab788fdd0d6636f946729c3fa1456ec2858db472',
			],
			[
				'steamID' => 76561198080938830,
				'name' => 'dragongun100',
				'avatarHash' => 'ab788fdd0d6636f946729c3fa1456ec2858db472',
			]
		]);
		Build::factory()->times(100)->create();
		BugReport::factory()->times(100)->create();
	}
}
