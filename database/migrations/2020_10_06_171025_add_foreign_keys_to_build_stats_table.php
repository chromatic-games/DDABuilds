<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBuildStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('build_stats', function (Blueprint $table) {
            $table->foreign('buildID', 'build_stats_ibfk_1')->references('id')->on('builds')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('classID', 'build_stats_ibfk_2')->references('id')->on('classes')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('build_stats', function (Blueprint $table) {
            $table->dropForeign('build_stats_ibfk_1');
            $table->dropForeign('build_stats_ibfk_2');
        });
    }
}
