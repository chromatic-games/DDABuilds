<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildCommentResource;
use App\Models\Build;
use App\Models\Build\BuildComment;
use Illuminate\Http\Request;

class BuildCommentController extends AbstractController {
	public function __construct() {
		$this->middleware('can:view,build');
		$this->authorizeResource(BuildComment::class);
	}

	public function index(Build $build) {
		return BuildCommentResource::collection(
			$build->commentList()->with(['user', 'likeValue'])->simplePaginate()
		);
	}

	public function store(Request $request, Build $build) {
		$values = $request->validate([
			'description' => 'required|string',
		]);

		$comment = $build->commentList()->create(array_merge($values, [
			'date' => time(),
			'steamID' => auth()->id(),
		]));
		$comment->loadMissing(['user']);

		return new BuildCommentResource($comment);
	}
}