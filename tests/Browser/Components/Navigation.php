<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use Tests\Browser\Traits\TBrowserHelper;

class Navigation extends BaseComponent {
	use TBrowserHelper;

	public function selector() {
		return $this->getVueSelector('navigation');
	}

	public function assert(Browser $browser) {
		$browser->assertVisible($this->selector());
	}

	public function navigateTo(Browser $I, string $menuTitle) {
		$I->clickLink($menuTitle, '@mainMenu a');
	}

	public function selectLanguage(Browser $I, string $language) {
		$I->click('@language');
		$I->clickLink($language);
		$I->waitForTextIn('@language', $language);
	}

	public function assertLogoutMenu(Browser $I) {
		$I->assertDontSeeIn('@mainMenu', 'Create build');
		$I->assertDontSeeIn('@mainMenu', 'Report Bug');
		$I->assertDontSeeIn('@mainMenu', 'Bug Reports');
	}

	public function elements() {
		return [
			'@mainMenu' => $this->getVueSelector('menu-navigation'),
			'@language' => $this->getVueSelector('dropdown', 'language') . ' a',
		];
	}
}