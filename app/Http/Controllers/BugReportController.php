<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BugReportController extends AbstractController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		if ( !auth()->id() ) {
			return response()->apiMissingAuthorization();
		}

		return BugReport::simplePaginate();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	public function show($id) {
		$bugReport = BugReport::find($id);
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
		$bugReport = BugReport::find($id);
		if ( $bugReport === null ) {
			return response()->apiBadRequest();
		}

		return response()->json(['status' => 'OK']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function destroy($id) {
		$bugReport = BugReport::find($id);
		if ( $bugReport === null ) {
			return response()->apiBadRequest();
		}
	}
}