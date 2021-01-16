<?php

namespace App\Providers;

use App\Models\Build;
use App\Models\Issue;
use App\Policies\BuildPolicy;
use App\Policies\IssuePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
	protected $policies = [
		Build::class => BuildPolicy::class,
		Issue::class => IssuePolicy::class,
	];

	public function boot() {
		$this->registerPolicies();
	}
}
