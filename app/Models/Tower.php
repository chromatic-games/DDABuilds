<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represent a possible placement on the builder map (hero tower or a decoration symbol (like an arrow))
 *
 * @package App\Models
 */
class Tower extends Model {
	/** @inheritDoc */
	protected $table = 'tower';
}