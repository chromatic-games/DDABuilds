<?php

namespace Database\Seeders;

use App\Models\Build;
use App\Models\Build\BuildTower;
use App\Models\Build\BuildWave;
use App\Models\Hero;
use App\Models\Tower;
use Faker\Generator;
use Illuminate\Database\Seeder;

class BuildSeeder extends Seeder {
	public function run(Generator $faker) {
		// get all available towers there are costs units (buildable placements)
		$towers = Tower::all()->where('unitCost', '>', 0);

		$heros = Hero::all()->where('isHero', 1);

		// generate builds
		$builds = Build::factory()->times(10)->create();

		// generate stuff for builds
		$builds->each(function (Build $build) use ($faker, $towers, $heros) {
			for ( $i = 0, $max = $faker->numberBetween(1, 2);$i < $max;$i++ ) {
				// generate a random wave
				/** @var BuildWave $buildWave */
				$buildWave = BuildWave::create([
					'buildID' => $build->ID,
					'name' => 'wave '.$faker->word.($i + 1),
				]);

				// generate random towers for this wave
				for ( $j = 0, $maxTowers = $faker->numberBetween(4, 15);$j < $maxTowers;$j++ ) {
					/** @var Tower $tower */
					$tower = $towers->random();

					BuildTower::create([
						'buildWaveID' => $buildWave->waveID,
						'towerID' => $tower->ID,
						'rotation' => $tower->isRotatable ? $faker->numberBetween(0, 359) : 0,
						'overrideUnits' => $tower->isResizable ? $faker->numberBetween($tower->unitCost, $tower->maxUnitCost) : 0,
						'x' => $faker->numberBetween(0, 1100),
						'y' => $faker->numberBetween(0, 800),
					]);
				}
			}

			foreach ( $heros->random(3) as $hero ) {
				$build->heroStats()->create([
					'heroID' => $hero->ID,
					'hp' => $faker->numberBetween(0, 4000),
					'damage' => $faker->numberBetween(0, 4000),
					'range' => $faker->numberBetween(0, 4000),
					'rate' => $faker->numberBetween(0, 4000),
				]);
			}

			for ( $i = 0, $count = $faker->numberBetween(0, 25);$i < $count;$i++ ) {
				$build->commentList()->create([
					'steamID' => $faker->steamID,
					'description' => substr($faker->sentences($faker->numberBetween(10, 100), true), 0, 1000),
					'date' => $faker->unixTime(),
				]);
			}

			$build->generateThumbnail();
		});
	}
}
