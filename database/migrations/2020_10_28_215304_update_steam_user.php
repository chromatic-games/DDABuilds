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
		    $table->renameColumn('steamID', 'ID');
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
		    $table->renameColumn('ID', 'steamID');
	    });
    }
}