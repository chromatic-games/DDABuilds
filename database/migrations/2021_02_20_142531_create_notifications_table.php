<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
		Schema::table('notifications', function (Blueprint $table) {
			$table->rename('notifications_old');
		});
		Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

		// TODO migrate notifications
		// 1 = new comment in build
		// 2 = build like
		// 3 = comment like
		// 4 = ignore (removed)

		Schema::dropIfExists('notifications_old');
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
