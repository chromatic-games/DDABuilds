<?php

namespace App\Observers;

use App\Models\Build;

class BuildObserver
{
	public function deleting(Build $build)
	{
		if ( file_exists($build->getPublicThumbnailPath()) ) {
			unlink($build->getPublicThumbnailPath());
		}
	}
}