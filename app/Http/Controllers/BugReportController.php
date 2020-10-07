<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestHttpException;
use App\Models\BugReport;
use Illuminate\Routing\Controller as BaseController;

class BugReportController extends BaseController {
	public function close($bugReportID) {
		$bugReport = BugReport::find($bugReportID);
		if ( $bugReport === null ) {
			throw new BadRequestHttpException();
		}

		return [
			'status' => 'OK',
		];
	}
}