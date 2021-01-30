<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @property-read int    $ID
 * @property-read string $languageCode
 * @property-read string $name
 */
class Locale extends AbstractModel {
	protected $table = 'locale';

	protected $primaryKey = 'ID';

	protected $fillable = [
		'languageCode',
		'name',
	];

	/**
	 * @return self[]
	 */
	public static function getLocales() {
		return Cache::remember('locales', 86400 * 7, function () {
			return Locale::get()->mapWithKeys(function ($value) {
				return [$value->languageCode => $value];
			})->all();
		});
	}

	public static function getSupportedLocales() : array {
		$locales = [];
		foreach ( self::getLocales() as $locale ) {
			$locales[] = $locale->languageCode;
		}

		return $locales;
	}
}