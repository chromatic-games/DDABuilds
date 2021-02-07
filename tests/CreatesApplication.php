<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Laravel\Dusk\Browser;

trait CreatesApplication {
	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication() {
		$app = require __DIR__.'/../bootstrap/app.php';

		$this->addMacros();

		$app->make(Kernel::class)->bootstrap();

		return $app;
	}

	public function addMacros() {
		Browser::macro('typeCkeditor', function (string $selector, string $value) {
			/** @var Browser $this */
			$this->type(
				$selector.' .ck-content',
				'_'.$value // first character not typed in the ckeditor
			);
		});

		Browser::macro('loginAsTester', function () {
			/** @var Browser $this */
			$this->loginAs(DuskTestCase::STEAM_TEST_USER_ID);
		});
	}
}
