<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteSession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('session', function (Blueprint $table) {
		    $table->drop();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::create('session', function (Blueprint $table) {
		    $table->string('sessionID', 128)->primary();
		    $table->string('steamID', 20);
		    $table->unsignedInteger('expires');
	    });
    }
}