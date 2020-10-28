<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBugReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('bug_report', function (Blueprint $table) {
		    $table->renameColumn('reportID', 'report_id');
		    $table->renameColumn('steamID', 'steam_id');
		    $table->string('title', 128)->change();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('bug_report', function (Blueprint $table) {
		    $table->renameColumn('report_id', 'reportID');
		    $table->renameColumn('steam_id', 'steamID');
		    $table->string('title', 64)->change();
	    });
    }
}