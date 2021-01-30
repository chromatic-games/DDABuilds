<?php

namespace App\Laravel\Database;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;

class DatabaseSessionHandler extends BaseDatabaseSessionHandler {
	protected function addUserInformation(&$payload)
	{
		if ($this->container->bound(Guard::class)) {
			$payload['steam_id'] = $this->userId();
		}

		return $this;
	}

	protected function addRequestInformation(&$payload) {
		return [];
	}
}