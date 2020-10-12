<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('build_stats', function (Blueprint $table) {
            $table->unsignedInteger('buildID');
            $table->unsignedInteger('classID')->index('classID');
            $table->unsignedInteger('hp')->nullable();
            $table->unsignedInteger('damage')->nullable();
            $table->unsignedInteger('range')->nullable();
            $table->unsignedInteger('rate')->nullable();
            $table->primary(['buildID', 'classID']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('build_stats');
    }
}
