<?php

namespace Tests;

use App\Models\SteamUser;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Faker\Generator;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use Tests\Browser\Traits\TBrowserHelper;

abstract class DuskTestCase extends BaseTestCase {
	use CreatesApplication;
	use TBrowserHelper;

	/**
	 * @var Generator
	 */
	public $faker;

	public static function prepare() {
		if ( !static::runningInSail() ) {
			static::startChromeDriver();
		}
	}

	public function setUp() : void {
		parent::setUp();

		Browser::$waitSeconds = 10;
		$this->faker = app(Generator::class);

		foreach (static::$browsers as $browser) {
			$browser->driver->manage()->deleteAllCookies();
		}
	}

	protected function driver() {
		$options = (new ChromeOptions)->addArguments([
			'--disable-gpu',
			'--headless',
			'--lang=en',
			'--window-size=1920,1080',
		]);

		return RemoteWebDriver::create(
			$_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
			DesiredCapabilities::chrome()->setCapability(
				ChromeOptions::CAPABILITY, $options
			)
		);
	}
}