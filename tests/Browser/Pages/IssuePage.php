<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\TBrowserHelper;

class IssuePage extends Page {
	use TBrowserHelper;

	public function url() {
		return '/';
	}

	public function assert(Browser $browser) {
		$browser->waitFor($this->getVueSelector('page', 'issueView'));
	}

	public function checkValues(Browser $I, string $title, string $description = null) {
		$I->waitForTextIn('@status', 'Open');
		$I->waitForTextIn('@title', $title);

		if ( $description ) {
			$I->waitForTextIn('@description', $description);
		}
	}

	public function elements() {
		return [
			'@status' => $this->getVueSelector('field', 'status'),
			'@title' => $this->getVueSelector('field', 'title'),
			'@description' => $this->getVueSelector('field', 'description'),
		];
	}
}