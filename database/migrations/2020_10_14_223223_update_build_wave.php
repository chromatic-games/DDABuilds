<?php

use App\Models\Build;
use App\Models\Build\BuildTower;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBuildWave extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$builds = Build::query()->with('waves')->get();

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
		}

		Schema::table('build_wave', function (Blueprint $table) {
			$table->unsignedInteger('fk_build')->after('id')->change();
		});
		Schema::table('build_wave', function (Blueprint $table) {
			$table->renameColumn('id', 'waveID');
			$table->renameColumn('fk_build', 'buildID');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('build_wave', function (Blueprint $table) {
			$table->unsignedInteger('ID')->after('name')->change();
		});
		Schema::table('build_wave', function (Blueprint $table) {
			$table->renameColumn('waveID', 'id');
			$table->renameColumn('buildID', 'fk_build');
		});
	}
}