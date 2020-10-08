<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Models\Build;
use Illuminate\Http\Request;

class BugReportController extends AbstractController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return Build::factory()->count(100)->make();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$bugReport = BugReport::find($id);
		if ( $bugReport === null ) {
			return $this->sendBadRequest();
		}

		return $bugReport;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int                      $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$bugReport = BugReport::find($id);
		if ( $bugReport === null ) {
			return $this->sendBadRequest();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$bugReport = BugReport::find($id);
		if ( $bugReport === null ) {
			return $this->sendBadRequest();
		}
	}
}