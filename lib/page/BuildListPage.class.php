<?php

namespace page;

use data\build\Build;
use data\build\BuildList;
use data\difficulty\Difficulty;
use data\difficulty\DifficultyList;
use data\gamemode\Gamemode;
use data\gamemode\GamemodeList;
use data\map\Map;
use data\map\MapList;
use system\cache\runtime\BuildStatusRuntimeCache;
use system\cache\runtime\DifficultyRuntimeCache;
use system\cache\runtime\GamemodeRuntimeCache;
use system\cache\runtime\MapRuntimeCache;
use system\Core;
use system\request\LinkHandler;
use system\util\HeaderUtil;

class BuildListPage extends SortablePage {
	/** @inheritDoc */
	public $objectListClassName = BuildList::class;

	/** @inheritDoc */
	public $defaultSortField = 'id';

	/** @inheritDoc */
	public $defaultSortOrder = 'DESC';

	/** @inheritDoc */
	public $validSortFields = ['author', 'likes', 'map', 'name', 'views', 'date', 'difficulty', 'gamemodeID'];

	/** @inheritDoc */
	public $pageTitle = 'Build List';

	/** @inheritDoc */
	public $itemsPerPage = 21;

	//<editor-fold desc="filter">

	/**
	 * search by build name
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * search by author
	 *
	 * @var string
	 */
	public $author = '';

	/**
	 * difficulty name for search
	 *
	 * @var string
	 */
	public $difficulty = '';

	/**
	 * difficulty id for object list
	 *
	 * @var int
	 */
	public $difficultyID = 0;

	/**
	 * gamemode name for search
	 *
	 * @var string
	 */
	public $gamemode = '';

	/**
	 * gamemode id for object list
	 *
	 * @var int
	 */
	public $gamemodeID = 0;

	/**
	 * map name for search
	 *
	 * @var string
	 */
	public $map = '';

	/**
	 * map id for search
	 *
	 * @var int
	 */
	public $mapID = 0;

	//</editor-fold>

	/** @var Map[] */
	public $maps;

	/** @var Gamemode[] */
	public $gamemodes;

	/** @var Difficulty[] */
	public $difficulties;

	public $viewMode = 'grid';

	public $showFilter = true;

	/** @inheritDoc */
	public function readParameters() {
		parent::readParameters();

		if ( isset($_REQUEST['viewMode']) ) {
			$viewMode = trim(strtolower($_REQUEST['viewMode']));
			if ( in_array($viewMode, ['list', 'grid']) ) {
				$this->viewMode = $viewMode;
				HeaderUtil::setCookie('listViewMode', $viewMode);
			}
		}
		elseif ( isset($_COOKIE[COOKIE_PREFIX.'listViewMode']) ) {
			$this->viewMode = $_COOKIE[COOKIE_PREFIX.'listViewMode'];
		}

		if ( !$this->showFilter ) {
			return;
		}

		$difficulties = new DifficultyList();
		$difficulties->readObjects();
		$this->difficulties = $difficulties->getObjects();

		$maps = new MapList();
		$maps->readObjects();
		$this->maps = $maps->getObjects();

		$gamemodeList = new GamemodeList();
		$gamemodeList->readObjects();
		$this->gamemodes = $gamemodeList->getObjects();

		if ( !empty($_REQUEST['map']) ) {
			$map = (int) $_REQUEST['map'];
			if ( isset($this->maps[$map]) ) {
				$this->mapID = $this->maps[$map]->getObjectID();
				$this->map = $this->maps[$map]->name;
			}
			else {
				$map = Map::getByName($_REQUEST['map']);
				if ( $map->getObjectID() ) {
					$this->mapID = $map->getObjectID();
					$this->map = $map->name;
				}
			}
		}
		if ( !empty($_REQUEST['gamemode']) ) {
			$gamemode = (int) $_REQUEST['gamemode'];
			if ( isset($this->gamemodes[$gamemode]) ) {
				$this->gamemodeID = $this->gamemodes[$gamemode]->getObjectID();
				$this->gamemode = $this->gamemodes[$gamemode]->name;
			}
			else {
				$gamemode = Gamemode::getByName($_REQUEST['gamemode']);
				if ( $gamemode->getObjectID() ) {
					$this->gamemodeID = $gamemode->getObjectID();
					$this->gamemode = $gamemode->name;
				}
			}
		}
		if ( !empty($_REQUEST['difficulty']) ) {
			$difficulty = (int) $_REQUEST['difficulty'];
			if ( isset($this->difficulties[$difficulty]) ) {
				$this->difficultyID = $this->difficulties[$difficulty]->getObjectID();
				$this->difficulty = $this->difficulties[$difficulty]->name;
			}
			else {
				$difficulty = Difficulty::getByName($_REQUEST['difficulty']);
				if ( $difficulty->getObjectID() ) {
					$this->difficultyID = $difficulty->getObjectID();
					$this->difficulty = $difficulty->name;
				}
			}
		}
		if ( isset($_REQUEST['author']) ) {
			$this->author = trim($_REQUEST['author']);
		}
		if ( isset($_REQUEST['name']) ) {
			$this->name = trim($_REQUEST['name']);
		}

		if ( !empty($_POST) ) {
			// build link parameters
			$linkParameters = 'pageNo='.$this->pageNo.'&sortField='.$this->sortField.'&sortOrder='.$this->sortOrder;

			if ( $this->author ) {
				$linkParameters .= '&author='.$this->author;
			}
			if ( $this->map ) {
				$linkParameters .= '&map='.$this->map;
			}
			if ( $this->difficulty ) {
				$linkParameters .= '&difficulty='.$this->difficulty;
			}
			if ( $this->gamemode ) {
				$linkParameters .= '&gamemode='.$this->gamemode;
			}
			if ( $this->name ) {
				$linkParameters .= '&name='.$this->name;
			}

			$link = LinkHandler::getInstance()->getLink('BuildList', [], $linkParameters);
			HeaderUtil::redirect($link);
			exit;
		}
	}

	/** @inheritDoc */
	protected function initObjectList() {
		parent::initObjectList();

		$this->objectList->getConditionBuilder()->add('deleted = 0');

		if ( !$this->showFilter ) {
			return;
		}

		$this->objectList->getConditionBuilder()->add('(fk_buildstatus = 1 OR fk_user = ?)', [Core::getUser()->steamID]);

		if ( $this->name ) {
			$this->objectList->getConditionBuilder()->add('name LIKE ?', ['%'.$this->name.'%']);
		}
		if ( $this->author ) {
			$this->objectList->getConditionBuilder()->add('author LIKE ?', ['%'.$this->author.'%']);
		}
		if ( $this->mapID ) {
			$this->objectList->getConditionBuilder()->add('map = ?', [$this->mapID]);
		}
		if ( $this->gamemodeID ) {
			$this->objectList->getConditionBuilder()->add('gamemodeID = ?', [$this->gamemodeID]);
		}
		if ( $this->difficultyID ) {
			$this->objectList->getConditionBuilder()->add('difficulty = ?', [$this->difficultyID]);
		}
	}

	/** @inheritDoc */
	protected function readObjects() {
		parent::readObjects();

		$maps = [];
		$difficulties = [];
		$buildStatuses = [];
		$gamemodes = [];

		/** @var Build $build */
		foreach ( $this->objectList as $build ) {
			if ( !in_array($build->map, $maps) ) {
				$maps[] = $build->map;
			}
			if ( !in_array($build->difficulty, $difficulties) ) {
				$difficulties[] = $build->difficulty;
			}
			if ( !in_array($build->gamemodeID, $gamemodes) ) {
				$gamemodes[] = $build->gamemodeID;
			}
			if ( !in_array($build->fk_buildstatus, $buildStatuses) ) {
				$buildStatuses[] = $build->fk_buildstatus;
			}
		}

		// cache all required object ids
		MapRuntimeCache::getInstance()->cacheObjectIDs($maps);
		DifficultyRuntimeCache::getInstance()->cacheObjectIDs($difficulties);
		BuildStatusRuntimeCache::getInstance()->cacheObjectIDs($buildStatuses);
		GamemodeRuntimeCache::getInstance()->cacheObjectIDs($gamemodes);
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'controller'   => 'BuildList',
			'maps'         => $this->maps,
			'gamemodes'    => $this->gamemodes,
			'difficulties' => $this->difficulties,
			'showFilter'   => $this->showFilter,
			'name'         => $this->name,
			'author'       => $this->author,
			'map'          => $this->map,
			'mapID'        => $this->mapID,
			'difficulty'   => $this->difficulty,
			'difficultyID' => $this->difficultyID,
			'gamemode'     => $this->gamemode,
			'gamemodeID'   => $this->gamemodeID,
			'viewMode'     => $this->viewMode,
		]);
	}
}