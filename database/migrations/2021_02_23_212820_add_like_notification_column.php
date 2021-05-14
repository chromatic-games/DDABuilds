<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLikeNotificationColumn extends Migration {
	public function up() {
		Schema::table('like', function (Blueprint $table) {
			$table->uuid('notificationID')->nullable()->after('date');
		});
	}

	public function down() {
		Schema::table('like', function (Blueprint $table) {
			$table->removeColumn('notificationID');
		});
	}
}
