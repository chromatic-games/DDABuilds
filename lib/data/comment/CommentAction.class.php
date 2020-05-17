<?php

namespace data\comment;

use data\build\Build;
use data\DatabaseObjectAction;
use system\Core;
use system\exception\NamedUserException;
use system\exception\UserInputException;
use system\util\StringUtil;

class CommentAction extends DatabaseObjectAction {
	public function validateAdd() {
		if ( empty($this->parameters['text']) ) {
			throw new UserInputException('text');
		}
		if ( empty($this->parameters['buildID']) ) {
			throw new UserInputException('buildID');
		}

		$this->parameters['text'] = StringUtil::removeInsecureHtml(trim($this->parameters['text']));
		if ( empty($this->parameters['text']) ) {
			throw new UserInputException('text');
		}

		$build = new Build($this->parameters['buildID']);
		if ( !$build->getObjectID() || $build->deleted ) {
			throw new NamedUserException('This is an invalid or deleted build');
		}
		elseif ( $build->fk_buildstatus === 3 && !$build->isCreator() ) {
			throw new NamedUserException('This is a private build, you cant comment on it');
		}
	}

	public function add() {
		$commentAction = new CommentAction([], 'create', [
			'data' => [
				'steamid'  => Core::getUser()->steamID,
				'fk_build' => $this->parameters['buildID'],
				'date'     => date('Y-m-d H:i:s'),
				'comment'  => $this->parameters['text'],
			],
		]);

		return Core::getTPL()->render('comment', [
			'comment' => $commentAction->executeAction()['returnValues'],
		]);
	}
}