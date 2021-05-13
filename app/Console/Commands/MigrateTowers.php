<?php

namespace App\Console\Commands;

use App\Models\Build;
use App\Models\Build\BuildTower;
use Illuminate\Console\Command;

class MigrateTowers extends Command {
	protected $signature = 'migrate:towers';

	public function handle() : int {
		$builds = Build::query()->with('waves')->get();
		$progress = $this->getOutput()->createProgressBar(count($builds));

		/** @var Build $build */
		foreach ( $builds as $build ) {
			// get build waves
			$waves = $build->waves()->orderBy('waveID')->get()->pluck('waveID');

			// delete old towers
			BuildTower::query()
				->where([
					'fk_build' => $build->getKey(),
				])
				->where('fk_buildwave', '>', count($waves))
				->delete();

			// update waves there already exists with the wave id
			foreach ( $waves as $key => $waveID ) {
				BuildTower::query()
					->where([
						'fk_build' => $build->getKey(),
						'fk_buildwave' => $key + 1,
					])
					->update([
						'fk_buildwave' => $waveID,
					]);
			}

			// create the wave 0 as real wave
			$wave = $build->waves()->create([
				'name' => 'Build',
			]);

			// update towers with wave 0
			BuildTower::query()
				->where([
					'fk_build' => $build->getKey(),
					'fk_buildwave' => 0,
				])
				->update([
					'fk_buildwave' => $wave->getKey(),
				]);

			$progress->advance();
		}

		return 0;
	}
}
