<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildResource;
use App\Http\Resources\HeroResource;
use App\Http\Resources\MapResource;
use App\Models\Build;
use App\Models\Difficulty;
use App\Models\GameMode;
use App\Models\Hero;
use App\Models\Map;
use App\Models\MapCategory;
use App\Models\Tower;
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

	public function maps() {
		return MapCategory::with('maps')->get();
	}

	public function editor(Map $map) {
		return [
			'map' => $map,
			'heros' => Hero::with('towers')->get(),
			'towers' => Tower::all(),
			'difficulties' => Difficulty::all(),
			'gameModes' => GameMode::all(),
		];
	}

	public function store(Request $request) {
		return response()->json(['not implemented yet']);
	}

	public function show(Build $build) {
		$this->authorize('view', $build);

		$build->load(['map:ID,name', 'difficulty:ID,name', 'gameMode:ID,name', 'waves.towers']);

		return response()->json($build);
	}

	public function update(Request $request, Build $build) {
		response()->json([], 500); // TODO
	}

	public function destroy(Request $request, Build $build) {
		response()->json([], 500); // TODO
	}
}