<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDifficulty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('difficulty', function (Blueprint $table) {
		    $table->renameColumn('id', 'ID');
	    });
	    Schema::table('difficulty', function (Blueprint $table) {
		    $table->increments('id')->change();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('difficulty', function (Blueprint $table) {
		    $table->renameColumn('ID', 'id');
	    });
	    Schema::table('difficulty', function (Blueprint $table) {
		    $table->unsignedInteger('id')->change();
	    });
    }
}