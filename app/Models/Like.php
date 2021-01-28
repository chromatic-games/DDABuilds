<?php

namespace App\Models;

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
	];
}