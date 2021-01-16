<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// TODO properties
class GameMode extends AbstractModel
{
	/** @inheritdoc */
	protected $table = 'game_mode';

	/** @inheritdoc */
	protected $primaryKey = 'id';

	/** @inheritdoc  */
	public $timestamps = false;

	/** @inheritdoc  */
	protected $fillable = [
		'name'
	];
}
