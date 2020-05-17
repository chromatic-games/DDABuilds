<?php

namespace data\build;

use data\DatabaseObject;
use data\difficulty\Difficulty;
use data\IRouteObject;
use data\map\Map;
use system\Core;

/**
 * TODO add here @ property-read for all database columns
 *
 * Class Build
 * @package data\build
 *
 * @property-read string  $date
 * @property-read string  $author
 * @property-read string  $name
 * @property-read integer $views
 * @property-read integer $map
 * @property-read integer $difficulty
 * @property-read integer $votes
 * @property-read integer $fk_user
 */
class Build extends DatabaseObject implements IRouteObject {
	protected static $databaseTableName = 'builds';

	protected static $databaseTableIndexName = 'id';

	public function getDate() {
		return date('d F Y', strtotime($this->date));
	}

	public function getThumbnail() {
		$filename = 'assets/images/thumbnails/'.$this->getObjectID().'.png';
		if ( !file_exists(MAIN_DIR.$filename) ) {
			return 'https://via.placeholder.com/262x262?text=Placeholder';
		}

		return $filename;
	}

	public function isCreator() {
		return $this->fk_user === Core::getUser()->steamID;
	}

	/**
	 * @param string $base64
	 *
	 * @return bool
	 */
	public function saveScreenshot($base64) {
		if ( !$this->getObjectID() ) {
			return false;
		}

		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
		$img = imagecreatefromstring($data);

		$width = imagesx($img);
		$height = imagesy($img);
		$top = 0;
		$bottom = 0;
		$left = 0;
		$right = 0;

		$bgcolor = imagecolorat($img, $top, $left); // This works with any color, including transparent backgrounds
		for ( ;$top < $height;++$top ) {
			for ( $x = 0;$x < $width;++$x ) {
				if ( imagecolorat($img, $x, $top) != $bgcolor ) {
					break 2; //out of the 'top' loop
				}
			}
		}
		for ( ;$bottom < $height;++$bottom ) {
			for ( $x = 0;$x < $width;++$x ) {
				if ( imagecolorat($img, $x, $height - $bottom - 1) != $bgcolor ) {
					break 2; //out of the 'bottom' loop
				}
			}
		}
		for ( ;$left < $width;++$left ) {
			for ( $y = 0;$y < $height;++$y ) {
				if ( imagecolorat($img, $left, $y) != $bgcolor ) {
					break 2; //out of the 'left' loop
				}
			}
		}
		for ( ;$right < $width;++$right ) {
			for ( $y = 0;$y < $height;++$y ) {
				if ( imagecolorat($img, $width - $right - 1, $y) != $bgcolor ) {
					break 2; //out of the 'right' loop
				}
			}
		}
		$new_width = $width - ($left + $right);
		$new_height = $height - ($top + $bottom);
		$newimg = imagecreatetruecolor($new_width, $new_height);
		imagealphablending($newimg, false);
		imagesavealpha($newimg, true);

		imagecopy($newimg, $img, 0, 0, $left, $top, imagesx($newimg), imagesy($newimg));

		$lastimg = imagecreatetruecolor(200, 200);
		imagealphablending($lastimg, false);
		imagesavealpha($lastimg, true);

		imagecopyresampled($lastimg, $newimg, 0, 0, 0, 0, 200, 200, $new_width, $new_height);

		return imagepng($lastimg, MAIN_DIR.'assets/images/thumbnails/'.$this->getObjectID().'.png');
	}

	/**
	 * all available gamemodes (with modifier (hardcore/mix mode) combination information)
	 *
	 * @return \string[][]
	 */
	public static function getGamemodes() {
		return [
			['name' => 'Campaign', 'key' => 'campaign'],
			['name' => 'Survival', 'key' => 'survival'],
			['name' => 'Challenge', 'key' => 'challenge'],
			['name' => 'Pure Strategy', 'key' => 'purestrategy'],
			['name' => 'Mix Mode', 'key' => 'mixmode'],
		];
	}

	/**
	 * get the name of map
	 * TODO use runtimecache/cachebuilder stuff
	 *
	 * @return Map
	 */
	public function getMap() {
		return new Map($this->map);
	}

	/**
	 * get the name of difficulty
	 * TODO use runtimecache/cachebuilder stuff
	 *
	 * @return Difficulty
	 */
	public function getDifficulty() {
		return new Difficulty($this->difficulty);
	}

	public function getTitle() {
		return $this->name;
	}
}