<?php

namespace page;

use data\build\Build;
use data\build\BuildList;
use data\difficulty\DifficultyList;
use data\map\Map;
use data\map\MapList;
use system\cache\runtime\BuildStatusRuntimeCache;
use system\cache\runtime\DifficultyRuntimeCache;
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
	public $validSortFields = ['author', 'likes', 'map', 'name', 'views', 'date', 'difficulty'];

	public $pageTitle = 'Build List';

	//<editor-fold desc="filter">
	public $name = '';

	public $author = '';

	public $difficulty = 0;

	public $map = 0;

	public $mapName = '';

	//</editor-fold>

	/** @var Map[] */
	public $maps;

	public $viewMode = 'grid';

	public $showFilter = true;

	/** @inheritDoc */
	public function readParameters() {
		parent::readParameters();

		if ( isset($_REQUEST['viewMode']) ) {
			$viewMode = trim(strtolower($_REQUEST['viewMode']));
			if ( in_array($viewMode, ['list', 'grid']) ) {
				$this->viewMode = $viewMode;
				$_SESSION['buildListViewMode'] = $viewMode; // todo replace with cookie
			}
		}
		elseif ( isset($_SESSION['buildListViewMode']) ) {
			$this->viewMode = $_SESSION['buildListViewMode'];
		}

		if ( !$this->showFilter ) {
			return;
		}

		$difficulties = new DifficultyList();
		$difficulties->readObjects();
		Core::getTPL()->assign('difficulties', $difficulties);

		$maps = new MapList();
		$maps->readObjects();
		$this->maps = $maps->getObjects();
		if ( !isset($this->maps[$this->map]) ) {
			$this->map = 0;
		}

		if ( isset($_REQUEST['map']) ) {
			$this->map = (int) $_REQUEST['map'];
		}
		if ( isset($_REQUEST['difficulty']) ) {
			$this->difficulty = (int) $_REQUEST['difficulty'];
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

		$this->objectList->getConditionBuilder()->add('fk_buildstatus = 1 OR fk_user = ?', [Core::getUser()->steamID]);

		if ( $this->name ) {
			$this->objectList->getConditionBuilder()->add('name LIKE ?', ['%'.$this->name.'%']);
		}
		if ( $this->author ) {
			$this->objectList->getConditionBuilder()->add('author LIKE ?', ['%'.$this->author.'%']);
		}
		if ( $this->map ) {
			$this->objectList->getConditionBuilder()->add('map = ?', [$this->map]);
		}
		if ( $this->difficulty ) {
			$this->objectList->getConditionBuilder()->add('difficulty = ?', [$this->difficulty]);
		}
	}

	/** @inheritDoc */
	protected function readObjects() {
		parent::readObjects();

		$maps = [];
		$difficulties = [];
		$buildStatuses = [];
		/** @var Build $build */
		foreach ( $this->objectList as $build ) {
			if ( !in_array($build->map, $maps) ) {
				$maps[] = $build->map;
			}
			if ( !in_array($build->difficulty, $difficulties) ) {
				$difficulties[] = $build->difficulty;
			}
			if ( !in_array($build->fk_buildstatus, $buildStatuses) ) {
				$buildStatuses[] = $build->fk_buildstatus;
			}
		}

		// cache all required object ids
		MapRuntimeCache::getInstance()->cacheObjectIDs($maps);
		DifficultyRuntimeCache::getInstance()->cacheObjectIDs($difficulties);
		BuildStatusRuntimeCache::getInstance()->cacheObjectIDs($buildStatuses);
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'controller' => 'BuildList',
			'showFilter' => $this->showFilter,
			'name'       => $this->name,
			'author'     => $this->author,
			'map'        => $this->map,
			'difficulty' => $this->difficulty,
			'viewMode'   => $this->viewMode,
		]);
	}
}