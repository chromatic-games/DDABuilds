<?php

namespace App\Providers;

use App\Faker\Provider\FakerProvider;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    	// register the custom faker provider class
	    $this->app->singleton(Generator::class, function() {
		    $faker = Factory::create();
		    $faker->addProvider(new FakerProvider($faker));

		    return $faker;
	    });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
