<?php

namespace Tests\Browser\Tests;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class IssueTest extends DuskTestCase {
	public function testCreate() {
		$this->browse(function (Browser $I) {
			$I->loginAsTester();
			$I->visit('/');
			$I->waitForTextIn($this->getVueSelector('navigation'), 'Report Bug');
			$I->clickLink('Report Bug', $this->getVueSelector('navigation') . ' a');
			$I->waitForText('Issues reported here are community reviewed and the reviewers are not related to Chromatic Games or Dungeon Defenders: Awakened employees in anyway.');

			$title = $this->faker->words;
			$description = $this->faker->text;

			// fill input fields
			$I->type($this->getVueSelector('input', 'title'), $title);
			$I->typeCkeditor($this->getVueSelector('input', 'description'), $description);
			$I->click($this->getVueSelector('input', 'checkbox'));
			$I->pause(500);
			$I->click($this->getVueSelector('input', 'save'));

			// check text on view
			$I->waitFor($this->getVueSelector('page', 'issueView'));
			$I->waitForTextIn($this->getVueSelector('field', 'status'), 'Open');
			$I->waitForTextIn($this->getVueSelector('field', 'title'), $title);
			$I->waitForTextIn($this->getVueSelector('field', 'description'), $description);

			// check if action menu is not visible for normal user
			$I->assertMissing($this->getVueSelector('action-menu'));
		});
	}
}