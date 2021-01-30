<?php

namespace Database\Seeders;

use App\Models\Build;
use App\Models\Build\BuildTower;
use App\Models\Build\BuildWave;
use App\Models\Tower;
use Faker\Generator;
use Illuminate\Database\Seeder;

class BuildSeeder extends Seeder {
	public function run(Generator $faker) {
		// get all available towers there are costs units (buildable placements)
		$towers = Tower::all()->where('unitCost', '>', 0);

		// generate builds
		$builds = Build::factory()->times(50)->create();

		// generate stuff for builds
		$builds->each(function ($build) use ($faker, $towers) {
			for ( $i = 0, $max = $faker->numberBetween(1, 4);$i < $max;$i++ ) {
				// generate a random wave
				/** @var BuildWave $buildWave */
				$buildWave = BuildWave::create([
					'buildID' => $build->ID,
					'name' => 'wave '.$faker->word.($i + 1),
				]);

				// generate random towers for this wave
				for ( $j = 0, $maxTowers = $faker->numberBetween(3, 10);$j < $maxTowers;$j++ ) {
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

			// TODO generate random hero stats
		});
	}
}
