<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('builds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('author', 20);
            $table->string('name', 128);
            $table->unsignedInteger('map')->index('map');
            $table->unsignedInteger('difficulty')->index('difficulty');
            $table->text('description');
            $table->timestamp('date')->useCurrent();
            $table->string('fk_user', 20);
            $table->unsignedInteger('fk_buildstatus')->index('fk_buildstatus');
            $table->unsignedInteger('gamemodeID')->index('gamemodeID');
            $table->integer('hardcore')->nullable();
            $table->integer('afkable')->nullable();
            $table->integer('views')->default(0);
            $table->unsignedInteger('likes')->default(0);
            $table->unsignedInteger('comments')->default(0);
            $table->string('timePerRun', 20)->nullable()->default('');
            $table->string('expPerRun', 20)->nullable()->default('');
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('builds');
    }
}