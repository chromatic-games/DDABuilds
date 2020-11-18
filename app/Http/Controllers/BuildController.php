<?php

namespace App\Http\Controllers;

use App\Models\Build;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuildController extends AbstractController {
	use AuthorizesRequests;

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function index(Request $request) {
		return response()->json(
			Build::listSelect()
			     ->withMapName()
			     ->withGameModeName()
			     ->withDifficultyName()
			     ->sort($request->query('sortField'), $request->query('sortOrder'))
			     ->search([
				     'isDeleted'  => 0,
				     'title'      => $request->query('title'),
				     'author'     => $request->query('author'),
				     'map'        => $request->query('map'),
				     'difficulty' => $request->query('difficulty'),
				     'gameMode'   => $request->query('gameMode'),
			     ])
			     ->paginate()
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return JsonResponse
	 */
	public function create() {
		response()->json([], 500); // TODO
	}

	/**
	 * Get all build data for the view
	 *
	 *
	 * @param Build $build
	 *
	 * @return JsonResponse
	 * @throws AuthorizationException
	 */
	public function show(Build $build) {
		$this->authorize('view', $build);

		$build->load(['map:ID,name', 'difficulty:ID,name', 'gameMode:ID,name', 'waves.towers']);

		return response()->json($build);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param int     $id
	 *
	 * @return JsonResponse
	 */
	public function update(Request $request, Build $build) {
		response()->json([], 500); // TODO
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	public function destroy(Request $request, Build $build) {
		response()->json([], 500); // TODO
	}
}
