<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('placed', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fk_build')->index('fk_build');
            $table->unsignedInteger('fk_tower')->index('fk_tower');
            $table->integer('x');
            $table->integer('y');
            $table->integer('rotation');
            $table->integer('fk_buildwave')->default(0);
            $table->integer('override_du')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('placed');
    }
}
