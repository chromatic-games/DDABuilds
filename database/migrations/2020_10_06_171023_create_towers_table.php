<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('towers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('mu')->default(0);
            $table->unsignedInteger('unitcost');
            $table->unsignedSmallInteger('maxUnitCost');
            $table->unsignedInteger('manacost');
            $table->unsignedInteger('fk_class')->index('fk_class');
            $table->string('name');
        });

	    DB::table('towers')->insert([
		    ['id' => 1, 'mu' => 0, 'unitcost' => 3, 'maxUnitCost' => 0, 'manacost' => 3, 'fk_class' => 1, 'name' => 'Spiked Blockade',],
		    ['id' => 2, 'mu' => 0, 'unitcost' => 6, 'maxUnitCost' => 0, 'manacost' => 8, 'fk_class' => 1, 'name' => 'Harpoon Turret',],
		    ['id' => 3, 'mu' => 0, 'unitcost' => 4, 'maxUnitCost' => 0, 'manacost' => 4, 'fk_class' => 1, 'name' => 'Bouncer Blockade',],
		    ['id' => 4, 'mu' => 0, 'unitcost' => 7, 'maxUnitCost' => 0, 'manacost' => 1, 'fk_class' => 1, 'name' => 'Bowling Ball Turret',],
		    ['id' => 5, 'mu' => 0, 'unitcost' => 8, 'maxUnitCost' => 0, 'manacost' => 1, 'fk_class' => 1, 'name' => 'Slice N Dice Blockade',],
		    ['id' => 6, 'mu' => 0, 'unitcost' => 3, 'maxUnitCost' => 0, 'manacost' => 4, 'fk_class' => 2, 'name' => 'Magic Missile Tower',],
		    ['id' => 7, 'mu' => 0, 'unitcost' => 1, 'maxUnitCost' => 0, 'manacost' => 2, 'fk_class' => 2, 'name' => 'Elemental Barrier',],
		    ['id' => 8, 'mu' => 0, 'unitcost' => 5, 'maxUnitCost' => 0, 'manacost' => 8, 'fk_class' => 2, 'name' => 'Flameburst Tower',],
		    ['id' => 9, 'mu' => 0, 'unitcost' => 7, 'maxUnitCost' => 0, 'manacost' => 1, 'fk_class' => 2, 'name' => 'Lightning Tower',],
		    ['id' => 10, 'mu' => 0, 'unitcost' => 8, 'maxUnitCost' => 0, 'manacost' => 1, 'fk_class' => 2, 'name' => 'Deadly Striker Tower',],
		    ['id' => 11, 'mu' => 0, 'unitcost' => 3, 'maxUnitCost' => 0, 'manacost' => 4, 'fk_class' => 3, 'name' => 'Explosive Trap',],
		    ['id' => 12, 'mu' => 0, 'unitcost' => 3, 'maxUnitCost' => 0, 'manacost' => 3, 'fk_class' => 3, 'name' => 'Poison Gas Trap',],
		    ['id' => 13, 'mu' => 0, 'unitcost' => 4, 'maxUnitCost' => 0, 'manacost' => 6, 'fk_class' => 3, 'name' => 'Inferno Trap',],
		    ['id' => 14, 'mu' => 0, 'unitcost' => 3, 'maxUnitCost' => 0, 'manacost' => 7, 'fk_class' => 3, 'name' => 'Darkness Trap',],
		    ['id' => 15, 'mu' => 0, 'unitcost' => 3, 'maxUnitCost' => 0, 'manacost' => 8, 'fk_class' => 3, 'name' => 'Thunder Spike Trap',],
		    ['id' => 16, 'mu' => 0, 'unitcost' => 3, 'maxUnitCost' => 0, 'manacost' => 3, 'fk_class' => 4, 'name' => 'Ensnare Aura',],
		    ['id' => 17, 'mu' => 0, 'unitcost' => 5, 'maxUnitCost' => 0, 'manacost' => 5, 'fk_class' => 4, 'name' => 'Electric Aura',],
		    ['id' => 18, 'mu' => 0, 'unitcost' => 4, 'maxUnitCost' => 0, 'manacost' => 4, 'fk_class' => 4, 'name' => 'Healing Aura',],
		    ['id' => 19, 'mu' => 0, 'unitcost' => 5, 'maxUnitCost' => 0, 'manacost' => 6, 'fk_class' => 4, 'name' => 'Strength Drain Aura',],
		    ['id' => 20, 'mu' => 0, 'unitcost' => 5, 'maxUnitCost' => 0, 'manacost' => 1, 'fk_class' => 4, 'name' => 'Enrage Aura',],
		    ['id' => 21, 'mu' => 0, 'unitcost' => 2, 'maxUnitCost' => 5, 'manacost' => 0, 'fk_class' => 5, 'name' => 'Proton Beam',],
		    ['id' => 22, 'mu' => 0, 'unitcost' => 2, 'maxUnitCost' => 5, 'manacost' => 0, 'fk_class' => 5, 'name' => 'Blocking Field',],
		    ['id' => 23, 'mu' => 0, 'unitcost' => 1, 'maxUnitCost' => 3, 'manacost' => 0, 'fk_class' => 5, 'name' => 'Reflect Beam',],
		    ['id' => 24, 'mu' => 0, 'unitcost' => 2, 'maxUnitCost' => 6, 'manacost' => 0, 'fk_class' => 5, 'name' => 'Shock Beam',],
		    ['id' => 25, 'mu' => 0, 'unitcost' => 4, 'maxUnitCost' => 6, 'manacost' => 0, 'fk_class' => 5, 'name' => 'Overclock Beam',],
		    ['id' => 200, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 20, 'name' => 'Crystal Core',],
		    ['id' => 211, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 21, 'name' => 'Hint 1',],
		    ['id' => 212, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 21, 'name' => 'Hint 2',],
		    ['id' => 213, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 21, 'name' => 'Hint 3',],
		    ['id' => 214, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 21, 'name' => 'Hint 4',],
		    ['id' => 215, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 21, 'name' => 'Hint 5',],
		    ['id' => 222, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Black Arrow',],
		    ['id' => 223, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Black Arrow Head',],
		    ['id' => 224, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Black Arrow String',],
		    ['id' => 225, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Green Arrow',],
		    ['id' => 226, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Green Arrow Head',],
		    ['id' => 227, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Green Arrow String',],
		    ['id' => 228, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Blue Arrow',],
		    ['id' => 229, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Blue Arrow Head',],
		    ['id' => 230, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Blue Arrow String',],
		    ['id' => 231, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Yellow Arrow',],
		    ['id' => 232, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Yellow Arrow Head',],
		    ['id' => 233, 'mu' => 0, 'unitcost' => 0, 'maxUnitCost' => 0, 'manacost' => 0, 'fk_class' => 22, 'name' => 'Yellow Arrow String',],
	    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('towers');
    }
}
