<?php

namespace page;

use data\build\BuildList;
use data\map\Map;
use data\map\MapList;
use system\Core;
use system\request\LinkHandler;
use system\util\HeaderUtil;

class BuildListPage extends SortablePage {
	public $objectListClassName = BuildList::class;

	public $itemsPerPage = 3;

	public $defaultSortField = 'id';

	public $defaultSortOrder = 'DESC';

	public $validSortFields = ['author', 'rating', 'map', 'name', 'views', 'date', 'difficulty'];

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

	protected function initObjectList() {
		parent::initObjectList();

		if ( !$this->showFilter ) {
			return;
		}

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