<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BugReport extends Model {
	/** @inheritdoc */
	protected $table = 'bug_report';

	/** @inheritdoc */
	protected $perPage = 20;

	/** @inheritdoc */
	protected $primaryKey = 'reportID';
}