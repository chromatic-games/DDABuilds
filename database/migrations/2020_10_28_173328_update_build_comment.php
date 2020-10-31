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
		    $table->renameColumn('id', 'ID');
		    $table->renameColumn('steamid', 'steamID');
		    $table->renameColumn('fk_build', 'buildID');
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
		    $table->renameColumn('ID', 'id');
		    $table->renameColumn('steamID', 'steamid');
		    $table->renameColumn('buildID', 'fk_build');
		    $table->renameColumn('description', 'comment');
	    });
    }
}