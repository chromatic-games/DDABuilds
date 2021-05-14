<?php

namespace App\Models;

/**
 * @property-read int    $ID
 * @property-read string $name
 */
class GameMode extends AbstractModel {
	/** @inheritdoc */
	protected $table = 'game_mode';

	/** @inheritdoc */
	protected $primaryKey = 'id';

	/** @inheritdoc */
	public $timestamps = false;

	/** @inheritdoc */
	protected $fillable = [
		'name',
	];
}
