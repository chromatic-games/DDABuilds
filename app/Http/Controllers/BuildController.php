<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildResource;
use App\Models\Build;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuildController extends AbstractController {
	/**
	 * Display a listing of the resource.
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
			$query->where('fk_buildstatus', '=', 1); // TODO add OR with current steamID
		})->simplePaginate();

		return response()->json($paginate);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return JsonResponse
	 */
	public function create() {
		//
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
			return $this->sendBadRequest();
		}

		return $bugReport;
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
			return $this->sendBadRequest();
		}
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
			return $this->sendBadRequest();
		}
	}
}
