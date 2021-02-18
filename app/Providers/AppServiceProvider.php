<?php

namespace App\Providers;

use App\Faker\Provider\FakerProvider;
use App\Laravel\Database\DatabaseSessionHandler;
use App\Models\Build;
use App\Observers\BuildObserver;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	public function register() {
		// register the custom faker provider class
		$this->app->singleton(Generator::class, function () {
			$faker = Factory::create();
			$faker->addProvider(new FakerProvider($faker));

			return $faker;
		});

		// register custom session driver
		Session::extend('customDatabase', function (Application $app) {
			$config = $app->get('config');
			$connection = $app->get('db')->connection($config->get('session.connection'));
			$table = $config->get('session.table');
			$minutes = $config->get('session.lifetime');

			return new DatabaseSessionHandler($connection, $table, $minutes, $app);
		});
	}

	public function boot() {
		Build::observe(BuildObserver::class);
	}
}
