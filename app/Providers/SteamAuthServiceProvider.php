<?php

namespace App\Providers;

use App\Auth\SteamAuth;
use Illuminate\Support\ServiceProvider;

class SteamAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
	    $this->app->singleton(SteamAuth::class, function () {
		    return new SteamAuth($this->app->get('request'));
	    });
    }
}