<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBugReportComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('bug_report_comment', function (Blueprint $table) {
		    $table->renameColumn('commentID', 'comment_id');
		    $table->renameColumn('bugReportID', 'bug_report_id');
		    $table->renameColumn('steamID', 'steam_id');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('bug_report_comment', function (Blueprint $table) {
		    $table->renameColumn('comment_id', 'commentID');
		    $table->renameColumn('bug_report_id', 'bugReportID');
		    $table->renameColumn('steam_id', 'steamID');
	    });
    }
}