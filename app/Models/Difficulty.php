<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

// TODO properties
class Difficulty extends AbstractModel {
	use HasFactory;

	/** @inheritdoc */
	protected $table = 'difficulty';

	/** @inheritdoc */
	protected $primaryKey = 'id';

	/** @inheritdoc */
	public $timestamps = false;

	/** @inheritdoc */
	protected $fillable = [
		'name',
	];
}
