<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPlacedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('placed', function (Blueprint $table) {
            $table->foreign('fk_build', 'placed_ibfk_1')->references('id')->on('builds')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('fk_tower', 'placed_ibfk_2')->references('id')->on('towers')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('placed', function (Blueprint $table) {
            $table->dropForeign('placed_ibfk_1');
            $table->dropForeign('placed_ibfk_2');
        });
    }
}
