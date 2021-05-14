<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdatePatch121 extends Migration
{
    public function up()
    {
        DB::update('UPDATE map SET units = 125 WHERE name = "thePromenade"');
		DB::update('UPDATE tower SET manaCost = 100, unitCost = 6 WHERE name = "sliceNDiceBlockade"');
    }

    public function down()
    {
		DB::update('UPDATE map SET units = 140 WHERE name = "thePromenade"');
		DB::update('UPDATE tower SET manaCost = 140, unitCost = 8 WHERE name = "sliceNDiceBlockade"');
    }
}
