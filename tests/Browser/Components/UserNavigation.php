<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use Tests\Browser\Traits\TBrowserHelper;

class UserNavigation extends BaseComponent {
	use TBrowserHelper;

	public function selector() {
		return $this->getVueSelector('user-dropdown');
	}

	public function assert(Browser $browser) {
		$browser->assertVisible($this->selector());
	}

	public function navigateTo(Browser $I, string $menuTitle) {
		$I->click('> a');
		$I->clickLink($menuTitle);
	}
}