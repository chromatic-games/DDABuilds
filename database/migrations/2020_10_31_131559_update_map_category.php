<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMapCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('map_category', function (Blueprint $table) {
		    $table->renameColumn('id', 'ID');
		    $table->dropColumn('text');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('map_category', function (Blueprint $table) {
		    $table->renameColumn('ID', 'id');
		    $table->string('text');
	    });
    }
}
