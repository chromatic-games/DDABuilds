<?php

namespace data\build;

use data\build\stats\BuildStats;
use data\build\status\BuildStatus;
use data\comment\Comment;
use data\comment\CommentList;
use data\DatabaseObject;
use data\difficulty\Difficulty;
use data\gamemode\Gamemode;
use data\ILinkableObject;
use data\IRouteObject;
use data\map\Map;
use Exception;
use system\cache\runtime\BuildStatusRuntimeCache;
use system\cache\runtime\DifficultyRuntimeCache;
use system\cache\runtime\GamemodeRuntimeCache;
use system\cache\runtime\MapRuntimeCache;
use system\Core;
use system\request\LinkHandler;

/**
 * TODO add here @ property-read for all database columns
 *
 * Class Build
 * @package data\build
 *
 * @property-read string  $date
 * @property-read string  $author
 * @property-read string  $name
 * @property-read string  $expPerRun
 * @property-read string  $timePerRun
 * @property-read string  $description
 * @property-read integer $views
 * @property-read integer $gamemodeID
 * @property-read integer $map
 * @property-read integer $likeValue
 * @property-read integer $comments
 * @property-read integer $deleted
 * @property-read integer $fk_buildstatus
 * @property-read integer $afkable
 * @property-read integer $hardcore
 * @property-read integer $difficulty
 * @property-read integer $likes
 * @property-read integer $fk_user
 */
class Build extends DatabaseObject implements IRouteObject, ILinkableObject {
	protected static $databaseTableName = 'builds';

	protected static $databaseTableIndexName = 'id';

	/**
	 * @var bool
	 */
	public $__isWatched;

	/** @var BuildStats[] */
	protected $__stats;

	/** @var Comment[] */
	protected $__comments;

	/** @var array */
	protected $__placedTowers;

	/** @var array */
	protected $__customWaves;

	public function getDate() {
		return date('d F Y', strtotime($this->date));
	}

	/**
	 * get the build thumbnail
	 *
	 * @return string
	 */
	public function getThumbnail() {
		$filename = 'assets/images/thumbnails/'.$this->getObjectID().'.png';
		if ( !file_exists(MAIN_DIR.$filename) ) {
			return 'https://via.placeholder.com/262x262?text=Placeholder';
		}

		return $filename;
	}

	/**
	 * @return BuildStats[]
	 * @throws Exception
	 */
	public function getStats() {
		if ( !$this->getObjectID() ) {
			return [];
		}

		if ( $this->__stats === null ) {
			$statement = Core::getDB()->prepareStatement('SELECT * FROM build_stats WHERE buildID = ?');
			$statement->execute([$this->getObjectID()]);
			$this->__stats = [];
			/** @var BuildStats $buildStats */
			foreach ( $statement->fetchObjects(BuildStats::class) as $buildStats ) {
				$this->__stats[$buildStats->classID] = $buildStats;
			}
		}

		return $this->__stats;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getPlacedTowers() {
		if ( !$this->getObjectID() ) {
			return [];
		}

		if ( $this->__stats === null ) {
			$statement = Core::getDB()->prepareStatement('SELECT * FROM placed WHERE fk_build = ?');
			$statement->execute([$this->getObjectID()]);
			$this->__placedTowers = $statement->fetchAll();
		}

		return $this->__placedTowers;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getCustomWaves() {
		if ( !$this->getObjectID() ) {
			return [];
		}

		if ( $this->__customWaves === null ) {
			$statement = Core::getDB()->prepareStatement('SELECT * FROM buildwaves WHERE fk_build = ? ORDER BY id ASC');
			$statement->execute([$this->getObjectID()]);
			$this->__customWaves = $statement->fetchAll();
		}

		return $this->__customWaves;
	}

	/**
	 * @return Comment[]
	 * @throws Exception
	 */
	public function getComments() {
		if ( !$this->getObjectID() ) {
			return [];
		}

		if ( $this->__comments === null ) {
			$commentList = new CommentList();
			$commentList->getConditionBuilder()->add('fk_build = ?', [$this->getObjectID()]);
			$commentList->sqlOrderBy = 'date DESC';
			$commentList->sqlLimit = Comment::COMMENTS_PER_PAGE + 1;
			$commentList->readObjects();

			$this->__comments = $commentList->getObjects();
		}

		return $this->__comments;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function isWatched() {
		if ( $this->__isWatched === null ) {
			$statement = Core::getDB()->prepareStatement('SELECT buildID FROM build_watch WHERE buildID = ? AND steamID = ?');
			$statement->execute([
				$this->getObjectID(),
				Core::getUser()->steamID,
			]);
			$this->__isWatched = $statement->rowCount() > 0;
		}

		return $this->__isWatched;
	}

	public function getLikeValue() {
		if ( $this->likeValue === null ) {
			$this->data['likeValue'] = 0;
			if ( Core::getUser()->steamID ) {
				$statement = Core::getDB()->prepareStatement('SELECT likeValue FROM `like` WHERE objectType = ? AND objectID = ? AND steamID = ?');
				$statement->execute([
					'build',
					$this->getObjectID(),
					Core::getUser()->steamID,
				]);
				$like = $statement->fetch();
				if ( $like !== null ) {
					$this->data['likeValue'] = $like['likeValue'];
				}
			}
		}

		return $this->likeValue;
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
	 * @return Gamemode
	 * @throws Exception
	 */
	public function getGamemode() {
		return GamemodeRuntimeCache::getInstance()->getObject($this->gamemodeID);
	}

	public function getGamemodeName() {
		if ( $this->getGamemode() === null ) {
			return 'Unknown';
		}

		return $this->getGamemode()->name;
	}

	/**
	 * get the map object from this build
	 *
	 * @return Map
	 */
	public function getMap() {
		return MapRuntimeCache::getInstance()->getObject($this->map);
	}

	/**
	 * get the difficulty object from this build
	 *
	 * @return Difficulty
	 */
	public function getDifficulty() {
		return DifficultyRuntimeCache::getInstance()->getObject($this->difficulty);
	}

	/**
	 * get the build status object from this build
	 *
	 * @return BuildStatus
	 */
	public function getBuildStatus() {
		return BuildStatusRuntimeCache::getInstance()->getObject($this->fk_buildstatus);
	}

	/** @inheritDoc */
	public function getTitle() {
		return $this->name;
	}

	/** @inheritDoc */
	public function getLink() {
		return LinkHandler::getInstance()->getLink('Build', ['object' => $this]);
	}
}