<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBuildWatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('build_watch', function (Blueprint $table) {
		    $table->renameColumn('steamID', 'steam_id');
		    $table->renameColumn('buildID', 'build_id');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('build_watch', function (Blueprint $table) {
		    $table->renameColumn('steam_id', 'steamID');
		    $table->renameColumn('build_id', 'buildID');
	    });
    }
}