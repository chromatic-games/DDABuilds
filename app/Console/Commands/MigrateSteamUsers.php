<?php

namespace App\Console\Commands;

use App\Models\Build;
use App\Models\Issue;
use App\Models\IssueComment;
use App\Models\SteamUser;
use App\Services\Steam;
use Illuminate\Console\Command;

class MigrateSteamUsers extends Command {
	protected $signature = 'migrate:steam-users';

	public function handle() : int {
		$builds = Build::query()->whereDoesntHave('user')->get();
		$buildComments = Build\BuildComment::query()->whereDoesntHave('user')->get();
		$issues = Issue::query()->whereDoesntHave('user')->get();
		$issueComments = IssueComment::query()->whereDoesntHave('user')->get();
		$steamIDs = array_unique(array_merge(
			($builds->pluck('steamID')->all()),
			($issues->pluck('steamID')->all()),
			($issueComments->pluck('steamID')->all()),
			($buildComments->pluck('steamID')->all())
		));

		if ( count($steamIDs) === 0 ) {
			$this->info('all fine :)');

			return 0;
		}

		/** @var Steam $steam */
		$steam = app(Steam::class);

		$progress = $this->getOutput()->createProgressBar(count($steamIDs));
		$steamUsers = [];
		foreach ( $steamIDs as $steamID ) {
			$steamUsers[$steamID] = SteamUser::query()->forceCreate([
				'ID' => $steamID,
				'name' => 'Unknown User',
				'avatarHash' => '',
			]);
			$progress->advance();
		}

		sleep(1);
		$this->info(PHP_EOL.'users inserted in the database, now fetching from steam');

		$progress->setProgress(0);

		foreach ( $steamIDs as $steamID ) {
			$progress->advance();

			$userInfo = $steam->getUserInfo($steamID);
			if ( $userInfo === null ) {
				$this->error(sprintf('user %s not found on steam', $steamID));
				continue;
			}

			$steamUsers[$steamID]->update([
				'name' => $userInfo['personaname'],
				'avatarHash' => $userInfo['personaname'],
			]);
		}

		return 0;
	}
}
