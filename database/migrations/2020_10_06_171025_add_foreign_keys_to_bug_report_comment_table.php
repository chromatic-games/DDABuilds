<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBugReportCommentTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('bug_report_comment', function (Blueprint $table) {
			$table->foreign('bugReportID', 'bug_report_comment_ibfk_1')->references('reportID')->on('bug_report')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('bug_report_comment', function (Blueprint $table) {
			$table->dropForeign('bug_report_comment_ibfk_1');
		});
	}
}
