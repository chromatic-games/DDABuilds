<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\IssueComment;
use Illuminate\Database\Seeder;

class IssueSeeder extends Seeder {
	public function run() {
		Issue::factory()->times(50)->create()->each(function (Issue $issue) {
			$issue->comments()->saveMany(IssueComment::factory()->times(rand(0, 30))->make());
		});
	}
}