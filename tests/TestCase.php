<?php

namespace Tests;

use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

	/**
	 * @var Generator
	 */
	public $faker;

	public function setUp():void {
		parent::setUp();

		$this->faker = app(Generator::class);
	}

	public function loginAsTester() {
		$this->actingAs($this->getTestUser());
	}

	public function loginAsSubTester() {
		$this->actingAs($this->getSubTestUser());
	}
}