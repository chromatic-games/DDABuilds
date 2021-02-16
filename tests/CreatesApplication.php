<?php

namespace Tests;

use App\Models\SteamUser;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Dusk\Browser;

trait CreatesApplication {
	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication() {
		$app = require __DIR__.'/../bootstrap/app.php';

		if ( !defined('LARAVEL_START') ) {
			define('LARAVEL_START', microtime(true));
		}

		$this->addMacros();

		$app->make(Kernel::class)->bootstrap();

		return $app;
	}

	/**
	 * @return SteamUser|Builder
	 */
	public function getTestUser() {
		/** @var SteamUser $testUser */
		static $testUser;

		if ( $testUser === null ) {
			$testUser = SteamUser::query()->firstOrCreate([
				'ID' => 1337,
			], [
				'name' => 'DuskTest',
				'avatarHash' => 'ab788fdd0d6636f946729c3fa1456ec2858db472',
			]);

			// create a second user to test permissions
			SteamUser::query()->firstOrCreate(['ID' => 1336], [
				'name' => 'DuskSecondTest',
				'avatarHash' => 'ab788fdd0d6636f946729c3fa1456ec2858db472',
			]);
		}

		return $testUser;
	}

	public function addMacros() {
		Browser::macro('typeCkeditor', function (string $selector, string $value) {
			/** @var Browser $this */
			$this->type(
				$selector.' .ck-content',
				'_'.$value // first character not typed in the ckeditor
			);

			$this->pause(500); // wait for ckeditor value
		});

		$self = $this;
		Browser::macro('loginAsTester', function () use ($self) {
			/** @var Browser $this */
			$this->loginAs($self->getTestUser()->ID);
		});
	}
}