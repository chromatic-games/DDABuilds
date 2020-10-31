<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Difficulty extends Model
{
	/** @inheritdoc */
	protected $table = 'difficulty';

	/** @inheritdoc */
	protected $primaryKey = 'id';

	/** @inheritdoc  */
	public $timestamps = false;

	/** @inheritdoc  */
	protected $fillable = [
		'name'
	];
}
