<?php

namespace action;

use data\build\Build;
use data\build\BuildAction;
use data\build\status\BuildStatus;
use data\difficulty\Difficulty;
use data\map\Map;
use system\Core;
use system\exception\UserInputException;

class SaveMapAction extends AjaxAction {
	public $loginRequired = true;

	public function execute() {
		parent::execute();

		foreach ( ['author', 'buildName', 'mapID', 'difficulty', 'buildStatus', 'afkAble', 'hardcore', 'towers'] as $field ) {
			if ( empty($_POST[$field]) ) {
				throw new UserInputException($field);
			}
		}

		$author = substr(trim($_POST['author']), 0, 20);
		$buildName = substr(trim($_POST['buildName']), 0, 128);

		if ( empty($author) ) {
			throw new UserInputException('author');
		}
		if ( empty($buildName) ) {
			throw new UserInputException('buildName');
		}

		// validate name
		$map = new Map($_POST['mapID']);
		if ( !$map->getObjectID() ) {
			throw new UserInputException('mapID', 'invalid');
		}

		// validate difficulty
		$difficulty = new Difficulty($_POST['difficulty']);
		if ( !$difficulty->getObjectID() ) {
			throw new UserInputException('difficulty', 'invalid');
		}

		// validate build status
		$buildStatus = new BuildStatus($_POST['buildStatus']);
		if ( !$buildStatus->getObjectID() ) {
			throw new UserInputException('buildStatus', 'invalid');
		}

		$gamemodes = Build::getGamemodes();
		$gamemode = null;
		foreach ( $gamemodes as $mode ) {
			if ( $_POST['gamemode'] === $mode['key'] ) {
				$gamemode = $mode['key'];
				break;
			}
		}

		if ( $gamemode === null ) {
			throw new UserInputException('gamemode', 'invalid');
		}

		$buildAction = new BuildAction([], 'create', [
			'image'  => !empty($_POST['image']) ? $_POST['image'] : null,
			'stats'  => !empty($_POST['stats']) ? $_POST['stats'] : [],
			'towers' => $_POST['towers'],
			'waves'  => isset($_POST['customWaves']) ? $_POST['customWaves'] : [],
			'data'   => [
				'author'         => $author,
				'name'           => $buildName,
				'map'            => $_POST['mapID'],
				'difficulty'     => $_POST['difficulty'],
				'afkable'        => $_POST['afkAble'] ? 1 : 0,
				'hardcore'       => $_POST['hardcore'] ? 1 : 0,
				'description'    => isset($_POST['description']) ? $_POST['description'] : '',
				'timePerRun'     => isset($_POST['timePerRun']) ? $_POST['timePerRun'] : '',
				'expPerRun'      => isset($_POST['expPerRun']) ? $_POST['expPerRun'] : '',
				'views'          => 0,
				'votes'          => 0,
				'date'           => date('Y-m-d H:i:s'),
				'fk_user'        => Core::getUser()->steamID,
				'fk_buildstatus' => $_POST['buildStatus'],
				$gamemode        => 1,
			],
		]);

		return $buildAction->executeAction();
	}
}