<?php

namespace App\Http\Controllers;

use App\Http\Resources\IssueResource;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IssueController extends AbstractController {
	public function __construct() {
		$this->authorizeResource(Issue::class);
	}

	public function index(Request $request) {
		if ( !auth()->id() ) {
			throw new AccessDeniedHttpException();
		}

		$issues = Issue::orderBy('time', 'DESC');
		if ( $request->query->getBoolean('mine') ) {
			$issues->where('steamID', auth()->id());
		}

		return IssueResource::collection($issues->simplePaginate());
	}

	public function store(Request $request) {
		$values = $this->validate($request, [
			'title' => 'required|min:3|max:128',
			'description' => 'nullable|string',
		]);

		/** @var Issue $issue */
		$issue = Issue::select(['time'])->where([['steamID', auth()->id()], [DB::raw('UNIX_TIMESTAMP() - time'), '<', Issue::WAIT_TIME]])->first();
		if ( $issue ) {
			return response()->json([
				'needWait' => Issue::WAIT_TIME - (time() - $issue->time),
			]);
		}

		$values['description'] = $values['description'] ?? '';

		return new IssueResource(Issue::create(array_merge($values, [
			'time' => time(),
			'steamID' => auth()->id(),
			'status' => Issue::STATUS_OPEN,
		])));
	}

	public function show(Issue $issue) {
		$issue->load('user');

		return new IssueResource($issue);
	}

	public function update(Request $request, Issue $issue) {
		$success = false;
		if ( $issue->status === Issue::STATUS_OPEN && $request->request->getInt('status', -1) === Issue::STATUS_CLOSED ) {
			$success = $issue->update([
				'status' => Issue::STATUS_CLOSED,
			]);
		}

		if ( !$success ) {
			throw new BadRequestHttpException();
		}

		return response()->noContent();
	}

	// not implemented, required?
	// public function destroy(Issue $issue) {
	// }
}