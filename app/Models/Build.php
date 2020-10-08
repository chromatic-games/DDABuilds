<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;

	/** @inheritdoc */
	protected $table = 'builds';

	/** @inheritdoc */
	protected $perPage = 21;

	/** @inheritdoc */
	protected $primaryKey = 'id';

	/** @inheritdoc  */
	public $timestamps = false;

	/** @inheritdoc  */
	protected $fillable = [
		'author',
		'name',
		'map',
		'difficulty',
		'description',
		'fk_buildstatus',
		'gamemodeID',
		'hardcore',
		'afkable',
		'timePerRun',
		'expPerRun',
	];
}
