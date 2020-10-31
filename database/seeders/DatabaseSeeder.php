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
		// generate steam user if no steam users available
		if ( SteamUser::all()->count() === 0 ) {
			$steamUsers = [
				[
					'ID'         => 76561198054589426,
					'name'       => 'derpierre65',
					'avatarHash' => 'ab788fdd0d6636f946729c3fa1456ec2858db472',
				],
				[
					'ID'         => 76561198080938830,
					'name'       => 'dragongun100',
					'avatarHash' => 'ab788fdd0d6636f946729c3fa1456ec2858db472',
				],
			];
			foreach ( $steamUsers as $steamUser ) {
				SteamUser::create($steamUser);
			}
		}

		// generate random builds
		Build::factory()->times(300)->create();

		// generate random bug reports
		BugReport::factory()->times(20)->create();
	}
}
