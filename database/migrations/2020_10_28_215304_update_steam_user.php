<?php

use App\Models\Build;
use App\Models\Issue;
use App\Models\IssueComment;
use App\Models\Like;
use App\Models\SteamUser;
use App\Services\Steam;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSteamUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('steam_user', function (Blueprint $table) {
		    $table->renameColumn('steamID', 'ID');
	    });

	    $builds = Build::query()->whereDoesntHave('user')->get();
	    $buildComments = Build\BuildComment::query()->whereDoesntHave('user')->get();
	    $issues = Issue::query()->whereDoesntHave('user')->get();
	    $issueComments = IssueComment::query()->whereDoesntHave('user')->get();
	    $likes = Like::query()->whereDoesntHave('user')->get();
	    $steamIDs = array_unique(array_merge(
		    ($builds->pluck('steamID')->all()),
		    ($issues->pluck('steamID')->all()),
		    ($issueComments->pluck('steamID')->all()),
		    ($buildComments->pluck('steamID')->all()),
		    ($likes->pluck('steamID')->all())
	    ));

	    if ( count($steamIDs) === 0 ) {
		    return;
	    }

	    /** @var Steam $steam */
	    $steam = app(Steam::class);

	    $steamUsers = [];
	    foreach ( $steamIDs as $steamID ) {
		    $steamUsers[$steamID] = SteamUser::query()->forceCreate([
			    'ID' => $steamID,
			    'name' => 'Unknown User',
			    'avatarHash' => '',
		    ]);
	    }

	    foreach ( $steamIDs as $steamID ) {
		    $userInfo = $steam->getUserInfo($steamID);
		    if ( $userInfo === null ) {
			    continue;
		    }

		    $steamUsers[$steamID]->update([
			    'name' => $userInfo['personaname'],
			    'avatarHash' => $userInfo['personaname'],
		    ]);
	    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('steam_user', function (Blueprint $table) {
		    $table->renameColumn('ID', 'steamID');
	    });
    }
}