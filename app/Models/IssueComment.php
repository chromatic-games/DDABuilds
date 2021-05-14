<?php

namespace App\Models;

use App\Models\Traits\HasSteamUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property-read int $commentID
 * @property-read int $bugReportID
 * @property-read int $time
 * @property-read string  $steamID
 * @property-read string  $description
 */
class IssueComment extends AbstractModel {
	use HasSteamUser, HasFactory;

	public const WAIT_TIME = 60;

	protected $table = 'bug_report_comment';

	public $timestamps = false;

	public $primaryKey = 'commentID';

	protected $fillable = [
		'commentID',
		'bugReportID',
		'description',
		'steamID',
		'time',
	];

	protected $hidden = [
		'steamID',
	];
}