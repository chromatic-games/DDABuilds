<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignKeys extends Migration {
	public function up() {
		Schema::table('bug_report_comment', function (Blueprint $table) {
			$table->dropForeign('bug_report_comment_ibfk_1');
		});
		Schema::table('build_stats', function (Blueprint $table) {
			$table->dropForeign('build_stats_ibfk_1');
			$table->dropForeign('build_stats_ibfk_2');
		});
		Schema::table('build_watch', function (Blueprint $table) {
			$table->dropForeign('build_watch_ibfk_1');
		});
		Schema::table('buildwaves', function (Blueprint $table) {
			$table->dropForeign('buildwaves_ibfk_1');
		});
		Schema::table('placed', function (Blueprint $table) {
			$table->dropForeign('placed_ibfk_1');
			$table->dropForeign('placed_ibfk_2');
		});
		Schema::table('towers', function (Blueprint $table) {
			$table->dropForeign('towers_ibfk_1');
		});
		Schema::table('builds', function (Blueprint $table) {
			$table->dropForeign('builds_ibfk_1');
			$table->dropForeign('builds_ibfk_2');
			$table->dropForeign('builds_ibfk_3');
			$table->dropForeign('builds_ibfk_4');
		});
	}

	public function down() {
		Schema::table('bug_report_comment', function (Blueprint $table) {
			$table->foreign('bugReportID', 'bug_report_comment_ibfk_1')->references('reportID')->on('bug_report')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
		Schema::table('build_stats', function (Blueprint $table) {
			$table->foreign('buildID', 'build_stats_ibfk_1')->references('id')->on('builds')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('classID', 'build_stats_ibfk_2')->references('id')->on('classes')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
		Schema::table('build_watch', function (Blueprint $table) {
			$table->foreign('buildID', 'build_watch_ibfk_1')->references('id')->on('builds')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
		Schema::table('buildwaves', function (Blueprint $table) {
			$table->foreign('fk_build', 'buildwaves_ibfk_1')->references('id')->on('builds')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
		Schema::table('placed', function (Blueprint $table) {
			$table->foreign('fk_build', 'placed_ibfk_1')->references('id')->on('builds')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('fk_tower', 'placed_ibfk_2')->references('id')->on('towers')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
		Schema::table('towers', function (Blueprint $table) {
			$table->foreign('fk_class', 'towers_ibfk_1')->references('id')->on('classes')->onUpdate('CASCADE')->onDelete('CASCADE');
		});

		Schema::table('builds', function (Blueprint $table) {
			$table->foreign('gamemodeID', 'builds_ibfk_1')->references('gamemodeID')->on('gamemode')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('map', 'builds_ibfk_2')->references('id')->on('maps')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('difficulty', 'builds_ibfk_3')->references('id')->on('difficulties')->onUpdate('CASCADE')->onDelete('NO ACTION');
			$table->foreign('fk_buildstatus', 'builds_ibfk_4')->references('id')->on('buildstatuses')->onUpdate('CASCADE')->onDelete('NO ACTION');
		});
	}
}