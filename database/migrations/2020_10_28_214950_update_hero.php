<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHero extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('hero', function (Blueprint $table) {
		    $table->renameColumn('isHero', 'is_hero');
		    $table->renameColumn('isDisabled', 'is_disabled');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('hero', function (Blueprint $table) {
		    $table->renameColumn('is_hero', 'isHero');
		    $table->renameColumn('is_disabled', 'isDisabled');
	    });
    }
}