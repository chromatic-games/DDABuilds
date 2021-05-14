<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteSession extends Migration
{
    public function up()
    {
    	Schema::dropIfExists('session');
    }

    public function down()
    {
	    Schema::create('session', function (Blueprint $table) {
		    $table->string('sessionID', 128)->primary();
		    $table->string('steamID', 20);
		    $table->unsignedInteger('expires');
	    });
    }
}