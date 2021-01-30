<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuildRequest;
use App\Http\Resources\BuildResource;
use App\Models\Build;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildController extends AbstractController {
	use AuthorizesRequests;

	public function index(Request $request) {
		$builds = Build::with(['map', 'gameMode', 'difficulty'])->sort($request->query('sortField'), $request->query('sortOrder'));
		if ( auth()->id() ) {
			if ( $request->query->getBoolean('mine') ) {
				$builds->where('build.steamID', auth()->id());
			}
			elseif ( $request->query->getBoolean('watch') ) {
				$builds
					->leftJoin('build_watch', function ($join) {
						$join->on('build.ID', 'build_watch.buildID');
						$join->where('build_watch.steamID', auth()->id());
					})
					->whereNotNull('build_watch.steamID');
			}
			elseif ( $request->query->getBoolean('liked') ) {
				$builds
					->leftJoin('like as like_object', function ($join) {
						$join->on('build.ID', 'like_object.objectID');
						$join->where([
							['like_object.objectType', DB::raw("'like'")],
							['like_object.steamID', auth()->id()],
						]);
					})
					->whereNotNull('like_object.steamID');
			}
		}

		return BuildResource::collection($builds->search([
			'isDeleted' => 0,
			'title' => $request->query('title'),
			'author' => $request->query('author'),
			'map' => $request->query('map'),
			'difficulty' => $request->query('difficulty'),
			'gameMode' => $request->query('gameMode'),
		])->simplePaginate());
	}

	public function store(BuildRequest $request) {
		$data = $request->all();
		/** @var Build $build */
		$build = Build::create(array_merge([
			'steamID' => auth()->id(),
			'description' => '',
		], $data));

		foreach ( $data['heroStats'] as $key => $heroStats ) {
			$heroStats['heroID'] = $key;
			$heroStats['buildID'] = $build->ID;
			$build->heroStats()->create($heroStats);
		}

		$waves = $waveTowers = [];
		foreach ( $data['towers'] as $tower ) {
			$waveTowers[$tower['waveID']] = $waveTowers[$tower['waveID']] ?? [];
			$waveTowers[$tower['waveID']][] = $tower;
		}

		foreach ( $data['waves'] as $key => $name ) {
			if ( empty($waveTowers[$key]) ) {
				continue;
			}

			/** @var Build\BuildWave $buildWave */
			$buildWave = $build->waves()->create([
				'name' => $name,
			]);
			$waves[$key] = $buildWave;
		}

		foreach ( $data['towers'] as $key => $tower ) {
			if ( !isset($waves[$tower['waveID']]) ) {
				continue;
			}

			$waves[$tower['waveID']]->towers()->create(array_merge($tower, [
				'towerID' => $tower['ID'],
				'overrideUnits' => $tower['size'],
			]));
		}

		return response()->json($build);
	}

	public function show(Build $build) {
		$this->authorize('view', $build);

		$build->load(['map:ID,name', 'difficulty:ID,name', 'gameMode:ID,name', 'waves.towers', 'heroStats']);

		return new BuildResource($build);
	}

	public function update(Request $request, Build $build) {
		response()->json([], 500); // TODO
	}

	public function destroy(Request $request, Build $build) {
		response()->json([], 500); // TODO
	}

	public function watch(Build $build) {
		$select = DB::select('SELECT * FROM build_watch WHERE buildID = ? AND steamID = ?', [
			$build->ID,
			auth()->id(),
		]);

		if ( count($select) > 0 ) {
			DB::select('DELETE FROM build_watch WHERE buildID = ? AND steamID = ?', [
				$build->ID,
				auth()->id(),
			]);

			$watchStatus = 0;
		}
		else {
			DB::insert('INSERT INTO build_watch (buildID, steamID) VALUES (?,?)', [
				$build->ID,
				auth()->id(),
			]);
			$watchStatus = 1;
		}

		return [
			'watchStatus' => $watchStatus,
		];
	}
}