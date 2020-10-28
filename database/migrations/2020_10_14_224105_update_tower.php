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
	    	$table->renameColumn('fk_class', 'hero_class_id');
	    	$table->renameColumn('manacost', 'mana_cost');
	    	$table->renameColumn('unitcost', 'unit_cost');
	    	$table->renameColumn('maxUnitCost', 'max_unit_cost');
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
		    $table->renameColumn('hero_class_id', 'fk_class');
		    $table->renameColumn('mana_cost', 'manacost');
		    $table->renameColumn('unit_cost', 'unitcost');
		    $table->renameColumn('max_unit_cost', 'maxUnitCost');
	    });
    }
}