<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMapcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapcategories', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name', 60);
            $table->string('text');
        });

	    DB::table('mapcategories')->insert([
		    ['id' => 1, 'name' => 'Campaign', 'text' => ''],
		    ['id' => 2, 'name' => 'Encore', 'text' => ''],
	    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mapcategories');
    }
}
