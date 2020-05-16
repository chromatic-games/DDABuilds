<?php

namespace page;

use data\build\Build;
use data\build\BuildList;
use system\Core;
use system\request\LinkHandler;
use system\util\HeaderUtil;

class ListPage extends AbstractPage {
	public function readParameters() {
		parent::readParameters();

		$buildList = new BuildList();
		$buildList->readObjects();

		$sortBy = !empty($_REQUEST['by']) ? $_REQUEST['by'] : 'id';
		$orderBy = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
		$linkParameters = 'by='.$sortBy.'&order='.$orderBy;

		$pageNo = 0;
		if ( isset($_REQUEST['pageNo']) ) {
			$pageNo = intval($_REQUEST['pageNo']);
		}

		foreach ( ['bname', 'map', 'difficulty', 'rating', 'author',] as $name ) {
			if ( !empty($_REQUEST[$name]) ) {
				$linkParameters .= '&'.$name.'='.$_REQUEST[$name];
			}
		}

		Core::getTPL()->assign([
			'author' => $_REQUEST['author'] ?? '',
			'bname' => $_REQUEST['bname'] ?? '',
			'map' => $_REQUEST['map'] ?? 0,
			'difficulty' => $_REQUEST['difficulty'] ?? 0,
		]);

		if ( !empty($_POST) ) {
			HeaderUtil::redirect(LinkHandler::getInstance()->getLink('List', [], ($pageNo ? 'pageNo='.$pageNo.'&' : '').$linkParameters));
			exit;
		}
	}
}