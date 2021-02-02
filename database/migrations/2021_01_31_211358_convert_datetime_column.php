<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConvertDatetimeColumn extends Migration {
	private $table = [
		'build' => ['date'],
		'build_comment' => ['date'],
		'like' => ['date'],
	];

	public function up() {
		foreach ( $this->table as $tableName => $columns ) {
			Schema::table($tableName, function (Blueprint $table) use ($columns) {
				foreach ( $columns as $column ) {
					$table->unsignedBigInteger('tmp_'.$column);
				}
			});

			foreach ( $columns as $column ) {
				DB::update('update `'.$tableName.'` SET tmp_'.$column.' = UNIX_TIMESTAMP('.$column.');');
			}

			Schema::table($tableName, function (Blueprint $table) use ($columns) {
				foreach ( $columns as $column ) {
					$table->dropColumn($column);
				}
			});

			Schema::table($tableName, function (Blueprint $table) use ($columns) {
				foreach ( $columns as $column ) {
					$table->renameColumn('tmp_'.$column, $column);
				}
			});
		}
	}

	public function down() {
		foreach ( $this->table as $tableName => $columns ) {
			Schema::table($tableName, function (Blueprint $table) use ($columns) {
				foreach ( $columns as $column ) {
					$table->timestamp('tmp_'.$column)->useCurrent();
				}
			});

			foreach ( $columns as $column ) {
				DB::update('update `'.$tableName.'` SET tmp_'.$column.' = FROM_UNIXTIME('.$column.');');
			}

			Schema::table($tableName, function (Blueprint $table) use ($columns) {
				foreach ( $columns as $column ) {
					$table->dropColumn($column);
				}
			});

			Schema::table($tableName, function (Blueprint $table) use ($columns) {
				foreach ( $columns as $column ) {
					$table->renameColumn('tmp_'.$column, $column);
				}
			});
		}
	}
}
