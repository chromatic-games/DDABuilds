<?php

namespace Tests\Browser\Tests;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\Navigation;
use Tests\Browser\Components\UserNavigation;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase {
	public function testLanguageSwitch() {
		$this->browse(function (Browser $I) {
			$I->loginAsTester();
			$I->visit('/');
			$I->within(new Navigation(), function (Browser $I) {
				$I->waitForTextIn('@mainMenu', 'Builds');
				$I->waitForTextIn('@mainMenu', 'Create build');
				$I->waitForTextIn('@mainMenu', 'Report Bug');
				$I->selectLanguage('Deutsch');
				$I->waitForTextIn('@mainMenu', 'Baupläne');
				$I->waitForTextIn('@mainMenu', 'Bauplan erstellen');
				$I->waitForTextIn('@mainMenu', 'Fehler melden');
			});
		});
	}

	public function testMenu() {
		$this->browse(function (Browser $I) {
			$I->visit('/');
			$I->within(new Navigation(), function (Browser $I) {
				$I->assertDontSeeIn('@mainMenu', 'Create build');
				$I->assertDontSeeIn('@mainMenu', 'Report Bug');
				$I->assertDontSeeIn('@mainMenu', 'Bug Reports');
			});

			$I->loginAsTester();
			$I->visit('/');
			$I->within(new Navigation(), function (Browser $I) {
				$I->assertSeeIn('@mainMenu', 'Create build');
				$I->assertSeeIn('@mainMenu', 'Report Bug');
				$I->assertDontSeeIn('@mainMenu', 'Bug Reports');
			});
			$I->within(new UserNavigation(), function (Browser $I) {
				$I->assertSeeIn('> a', 'DuskTest');
			});
		});
	}
}