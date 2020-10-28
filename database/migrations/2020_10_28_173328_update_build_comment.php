<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBuildComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('build_comment', function (Blueprint $table) {
		    $table->renameColumn('steamid', 'steam_id');
		    $table->renameColumn('fk_build', 'build_id');
		    $table->renameColumn('comment', 'description');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('build_comment', function (Blueprint $table) {
		    $table->renameColumn('steam_id', 'steamid');
		    $table->renameColumn('build_id', 'fk_build');
		    $table->renameColumn('description', 'comment');
	    });
    }
}