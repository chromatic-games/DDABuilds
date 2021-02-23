<?php

namespace App\Providers;

use App\Listeners\LikeNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSent;

class EventServiceProvider extends ServiceProvider {
	protected $listen = [
		NotificationSent::class => [
			LikeNotificationListener::class
		]
	];

	public function boot() {
		//
	}
}
