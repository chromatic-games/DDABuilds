<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use Tests\Browser\Traits\TBrowserHelper;

class UserNavigation extends BaseComponent {
	use TBrowserHelper;

	public function selector() {
		return $this->getVueSelector('dropdown', 'user');
	}

	public function assert(Browser $browser) {
		$browser->waitFor($this->selector());
	}

	public function navigateTo(Browser $I, string $menuTitle) {
		$I->click('@username');
		$I->clickLink($menuTitle);
	}

	public function elements() {
		return [
			'@username' => '> a',
		];
	}
}