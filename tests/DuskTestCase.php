<?php

namespace Tests;

use App\Models\SteamUser;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Faker\Generator;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase {
	use CreatesApplication;

	public const STEAM_TEST_USER_ID = '1337';

	/**
	 * @var Generator
	 */
	public $faker;

	public static function prepare() {
		if ( !static::runningInSail() ) {
			static::startChromeDriver();
		}
	}

	public function setUp():void {
		parent::setUp();

		$this->faker = app(Generator::class);

		// create test steam user if not exists
		SteamUser::query()->firstOrCreate([
			'ID' => self::STEAM_TEST_USER_ID,
		], [
			'ID' => self::STEAM_TEST_USER_ID,
			'name' => 'DuskTest',
			'avatarHash' => 'ab788fdd0d6636f946729c3fa1456ec2858db472',
		]);
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

	public function getVueSelector(string $arg, string $value = null) {
		$selectorValue = '';

		if ( $value !== null ) {
			$selectorValue = '="'.(string) $value.'"';
		}

		return '[data-test-selector-'.$arg.$selectorValue.']';
	}
}
