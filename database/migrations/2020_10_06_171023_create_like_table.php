<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('like', function (Blueprint $table) {
            $table->string('objectType', 16)->default('');
            $table->unsignedInteger('objectID');
            $table->string('steamID', 20);
            $table->tinyInteger('likeValue');
            $table->timestamp('date')->nullable();
            $table->unique(['objectType', 'objectID', 'steamID'], 'objectType');
            $table->primary(['objectType', 'objectID', 'steamID']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('like');
    }
}
