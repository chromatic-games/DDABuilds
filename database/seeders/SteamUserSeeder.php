<?php

namespace Database\Seeders;

use App\Models\SteamUser;
use Illuminate\Database\Seeder;

/**
 * Database seeder to generate static steam users
 *
 * @package Database\Seeders
 */
class SteamUserSeeder extends Seeder {
	/**
	 * Run the seeder for steam users.
	 *
	 * @return void
	 */
	public function run() {
		// generate steam user if no steam users available
		$steamUsers = [
			[
				'ID' => 76561198054589426,
				'name' => 'derpierre65',
				'avatarHash' => 'ab788fdd0d6636f946729c3fa1456ec2858db472',
			],
			[
				'ID' => 76561198080938830,
				'name' => 'dragongun100',
				'avatarHash' => 'ab788fdd0d6636f946729c3fa1456ec2858db472',
			],
		];
		foreach ( $steamUsers as $steamUser ) {
			if ( SteamUser::query()->find($steamUser['ID']) === null ) {
				SteamUser::create($steamUser);
			}
		}
	}
}
