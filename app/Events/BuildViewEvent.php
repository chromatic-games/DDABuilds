<?php

namespace App\Events;

use App\Models\Build;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Session\Store;

class BuildViewEvent {
	use Dispatchable;

	private $session;

	public function __construct(Build $build, Store $session) {
		$this->session = $session;

		if ( !$this->isViewed($build) ) {
			$build->increment('views');
			$this->store($build);
		}
	}

	private function store(Build $build) {
		$this->session->push('builds_viewed', $build->ID);
	}

	private function isViewed(Build $build) {
		return in_array($build->ID, $this->session->get('builds_viewed', []));
	}
}
