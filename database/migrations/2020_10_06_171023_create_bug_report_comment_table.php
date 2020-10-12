<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBugReportCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bug_report_comment', function (Blueprint $table) {
            $table->increments('commentID');
            $table->unsignedInteger('bugReportID')->index('bugReportID');
            $table->string('steamID', 20);
            $table->unsignedInteger('time')->default(0);
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bug_report_comment');
    }
}
