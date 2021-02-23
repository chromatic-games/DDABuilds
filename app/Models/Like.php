<?php

namespace App\Models;

/**
 * @property-read string $objectType
 * @property-read int $objectID
 * @property-read string $steamID
 * @property-read int $likeValue
 * @property-read string $date
 * @property-read string $notificationID
 */
class Like extends AbstractModel {
	protected $table = 'like';

	protected $primaryKey = null;

	public $incrementing = false;

	public $timestamps = false;

	protected $fillable = [
		'objectType',
		'objectID',
		'steamID',
		'likeValue',
		'date',
		'notificationID',
	];
}