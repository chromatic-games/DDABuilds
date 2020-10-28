<?php

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
		    $table->renameColumn('steamID', 'id');
		    $table->renameColumn('avatarHash', 'avatar_hash');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('steam_user', function (Blueprint $table) {
		    $table->renameColumn('id', 'steamID');
		    $table->renameColumn('avatar_hash', 'avatarHash');
	    });
    }
}