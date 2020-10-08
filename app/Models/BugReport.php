<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BugReport extends Model {
	use HasFactory;

	/** @inheritdoc */
	protected $table = 'bug_report';

	/** @inheritdoc */
	protected $perPage = 20;

	/** @inheritdoc */
	public $timestamps = false;

	/** @inheritdoc */
	protected $primaryKey = 'reportID';

	/** @inheritdoc */
	protected $fillable = [
		'title',
		'description',
	];
}