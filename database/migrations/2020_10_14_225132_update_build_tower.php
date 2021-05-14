<?php

use App\Models\Build;
use App\Models\Build\BuildTower;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBuildTower extends Migration
{
    public function up()
    {
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

	    Schema::table('build_tower', function (Blueprint $table) {
		    $table->renameColumn('fk_tower', 'towerID');
		    $table->renameColumn('fk_buildwave', 'buildWaveID');
		    $table->renameColumn('override_du', 'overrideUnits');
			$table->dropColumn('fk_build');
			$table->dropColumn('id');
	    });
	    Schema::table('build_tower', function (Blueprint $table) {
		    $table->unsignedInteger('x')->change();
		    $table->unsignedInteger('y')->change();
		    $table->unsignedInteger('rotation')->change();
		    $table->unsignedInteger('overrideUnits')->change();
		    $table->unsignedInteger('buildWaveID')->change();
	    });
    }

    public function down()
    {
	    Schema::table('build_tower', function (Blueprint $table) {
			$table->increments('id')->first();
			$table->unsignedInteger('fk_build')->index('fk_build')->after('id');
		    $table->integer('x')->change();
		    $table->integer('y')->change();
		    $table->integer('rotation')->change();
		    $table->integer('overrideUnits')->change();
		    $table->integer('buildWaveID')->change();
	    });
	    Schema::table('build_tower', function (Blueprint $table) {
		    $table->renameColumn('buildID', 'fk_build');
		    $table->renameColumn('towerID', 'fk_tower');
		    $table->renameColumn('buildWaveID', 'fk_buildwave');
		    $table->renameColumn('overrideUnits', 'override_du');
	    });
    }
}