<?php

namespace App\Http\Controllers;

use App\Models\Build;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuildController extends AbstractController {
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
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	public function show($id) {
		$bugReport = Build::find($id);
		if ( $bugReport === null ) {
			return response()->apiBadRequest();
		}

		return response()->json($bugReport);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param int     $id
	 *
	 * @return JsonResponse
	 */
	public function update(Request $request, $id) {
		$bugReport = Build::find($id);
		if ( $bugReport === null ) {
			return response()->apiBadRequest();
		}

		response()->json([], 500); // TODO
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	public function destroy($id) {
		$bugReport = Build::find($id);
		if ( $bugReport === null ) {
			return response()->apiBadRequest();
		}

		response()->json([], 500); // TODO
	}
}
