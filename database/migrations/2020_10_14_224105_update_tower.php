<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTower extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('tower', function (Blueprint $table) {
	    	$table->renameColumn('id', 'ID');
	    	$table->renameColumn('fk_class', 'heroClassID');
	    	$table->renameColumn('manacost', 'manaCost');
	    	$table->renameColumn('unitcost', 'unitCost');
	    	$table->renameColumn('mu', 'unitType');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('tower', function (Blueprint $table) {
		    $table->renameColumn('ID', 'id');
		    $table->renameColumn('heroClassID', 'fk_class');
		    $table->renameColumn('manaCost', 'manacost');
		    $table->renameColumn('unitCost', 'unitcost');
		    $table->renameColumn('unitType', 'mu');
	    });
    }
}