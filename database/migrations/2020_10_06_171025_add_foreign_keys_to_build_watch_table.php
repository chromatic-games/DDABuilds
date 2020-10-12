<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBuildWatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('build_watch', function (Blueprint $table) {
            $table->foreign('buildID', 'build_watch_ibfk_1')->references('id')->on('builds')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
            $table->dropForeign('build_watch_ibfk_1');
        });
    }
}
