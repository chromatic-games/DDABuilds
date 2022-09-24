<?php

use App\Models\Hero;
use App\Models\Map;
use App\Models\Tower;
use Illuminate\Database\Migrations\Migration;

class UpdateDefenseUnits extends Migration
{
	public function up()
	{
		// add act 4 maps
		!Map::query()->where(['name' => 'theMill'])->first() && Map::query()->forceCreate([
			'name' => 'theMill',
			'units' => 140,
			'mapCategoryID' => 1,
		]);
		!Map::query()->where(['name' => 'theOutpost'])->first() && Map::query()->forceCreate([
			'name' => 'theOutpost',
			'units' => 140,
			'mapCategoryID' => 1,
		]);
		!Map::query()->where(['name' => 'theKeep'])->first() && Map::query()->forceCreate([
			'name' => 'theKeep',
			'units' => 145,
			'mapCategoryID' => 1,
		]);

		// add new hero
		/** @var Hero $hero */
		$hero = Hero::query()->where(['name' => 'warden'])->first() ?? Hero::query()->create([
			'ID' => 6,
			'name' => 'warden',
			'isHero' => true,
			'isDisabled' => false,
		]);

		// add new towers
		$newTowers = [
			'rootsOfPurity' => [
				'ID' => 26,
				'manaCost' => 20,
				'unitCost' => 1,
				'isRotatable' => false,
			],
			'wispDen' => [
				'ID' => 27,
				'manaCost' => 50,
				'unitCost' => 4,
				'isRotatable' => false,
			],
			'beamingBlossom' => [
				'ID' => 28,
				'manaCost' => 70,
				'unitCost' => 5,
				'isRotatable' => true,
			],
			'shroomyPit' => [
				'ID' => 29,
				'manaCost' => 40,
				'unitCost' => 3,
				'isRotatable' => false,
			],
			'sludgeLauncher' => [
				'ID' => 30,
				'manaCost' => 120,
				'unitCost' => 7,
				'isRotatable' => true,
			],
		];

		foreach ( $newTowers as $towerName => $values ) {
			if ( Tower::query()->where(['name' => $towerName])->first() ) {
				continue;
			}

			Tower::query()->create(array_merge([
				'heroClassID' => 6,
				'unitType' => 0,
				'maxUnitCost' => 0,
				'name' => $towerName,
				'isResizable' => false,
			], $values));
		}

		// update tower defense unit values
		$updatedTowers = [
			// squire
			'spikedBlockade' => 1,
			'harpoonTurret' => 5,
			'bouncerBlockade' => 1,
			'sliceNDiceBlockade' => 4,
			// apprentice
			'magicMissileTower' => 2,
			// series
			'overclockBeam' => 3,
			// monk
			'strengthDrainAura' => 4,
		];

		foreach ( $updatedTowers as $towerName => $newUnits ) {
			Tower::query()->where(['name' => $towerName])->update([
				'unitCost' => $newUnits,
			]);
		}
	}

	public function down()
	{
	}
}