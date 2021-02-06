<?php

namespace App\Http\Controllers;

use App\Http\Resources\IssueCommentResource;
use App\Models\Issue;
use App\Models\IssueComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IssueCommentController extends AbstractController {
	public function __construct() {
		$this->middleware('can:view,issue');
	}

	public function index(Issue $issue) {
		return IssueCommentResource::collection($issue->getComments()->orderBy('time', 'DESC')->with('user')->simplePaginate());
	}

	public function store(Request $request, Issue $issue) {
		if ( $issue->status === Issue::STATUS_CLOSED ) {
			throw new AccessDeniedHttpException();
		}

		$values = $this->validate($request, [
			'description' => 'required|string',
		]);

		/** @var IssueComment $lastIssueComment */
		$lastIssueComment = IssueComment::select(['time'])->where([['steamID', auth()->id()], [DB::raw('UNIX_TIMESTAMP() - time'), '<', IssueComment::WAIT_TIME]])->first();
		if ( $lastIssueComment ) {
			return response()->json([
				'needWait' => IssueComment::WAIT_TIME - (time() - $lastIssueComment->time),
			]);
		}

		/** @var IssueComment $issueComment */
		$issueComment = $issue->comments()->create(array_merge($values, [
			'time' => time(),
			'steamID' => auth()->id(),
		]));
		$issueComment->load('user');

		return [
			'needWait' => IssueComment::WAIT_TIME,
			'comment' => new IssueCommentResource($issueComment),
		];
	}

	// public function show(IssueComment $issueComment) {
	// 	return new IssueCommentResource($issueComment);
	// }

	// public function update(Request $request, IssueComment $issueComment) {
	// }

	// public function destroy(IssueComment $issueComment) {
	// }
}
