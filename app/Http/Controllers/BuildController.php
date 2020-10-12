<?php

namespace App\Http\Controllers;

use App\Models\Build;
use Illuminate\Database\Query\Builder;
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
		$where = [
			['deleted', '=', 0],
		];

		if ( $request->query('name') ) {
			$where[] = ['name', 'like', '%'.$request->query('name').'%'];
		}
		if ( $request->query('author') ) {
			$where[] = ['author', 'like', '%'.$request->query('author').'%'];
		}
		if ( $request->query('mapID') ) {
			$where[] = ['map', '=', $request->query('mapID')];
		}
		if ( $request->query('gamemodeID') ) {
			$where[] = ['gamemodeID', '=', $request->query('gamemodeID')];
		}
		if ( $request->query('difficulty') ) {
			$where[] = ['difficulty', '=', $request->query('difficulty')];
		}

		$paginate = Build::where($where)->whereNested(function (Builder $query) {
			$query->where('fk_buildstatus', '=', 1);
			if ( auth()->id() ) {
				$query->orWhere('fk_user', '=', auth()->id());
			}
		})->simplePaginate();

		return response()->json($paginate);
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
