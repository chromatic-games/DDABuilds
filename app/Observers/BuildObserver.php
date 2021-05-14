<?php

namespace App\Observers;

use App\Models\Build;

class BuildObserver {
	public function deleting(Build $build) {
		unlink($build->getPublicThumbnailPath());
	}
}