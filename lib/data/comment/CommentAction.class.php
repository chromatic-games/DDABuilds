<?php

namespace data\comment;

use data\build\Build;
use data\DatabaseObjectAction;
use data\notification\NotificationAction;
use system\Core;
use system\exception\NamedUserException;
use system\exception\UserInputException;
use system\util\StringUtil;

class CommentAction extends DatabaseObjectAction {
	/**
	 * @var Build
	 */
	public $build;

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

		$this->build = new Build($this->parameters['buildID']);
		if ( !$this->build->getObjectID() || $this->build->deleted ) {
			throw new NamedUserException('This is an invalid or deleted build');
		}
		elseif ( $this->build->fk_buildstatus === 3 && !$this->build->isCreator() ) {
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
		/** @var Comment $newComment */
		$newComment = $commentAction->executeAction()['returnValues'];

		$this->build->updateCounters(['comments' => 1]);

		// get involved users
		$statement = Core::getDB()->prepareStatement('SELECT steamid FROM comments WHERE fk_build = ? AND steamid <> ? AND steamid <> ? GROUP BY steamid');
		$statement->execute([$this->build->getObjectID(), $this->build->fk_user, Core::getUser()->steamID]);
		foreach ( $statement->fetchAll(\PDO::FETCH_COLUMN) as $steamid ) {
			$notificationAction = new NotificationAction([], 'create', [
				'data' => [
					'steamid'    => $steamid,
					'data'       => Core::getUser()->steamID,
					'type'       => 4,
					'fk_build'   => $this->build->getObjectID(),
					'fk_comment' => $newComment->getObjectID(),
				],
			]);
			$notificationAction->executeAction();
		}

		// add notification for comment on build
		$notificationAction = new NotificationAction([], 'create', [
			'data' => [
				'steamid'    => $this->build->fk_user,
				'data'       => Core::getUser()->steamID,
				'type'       => 1,
				'fk_build'   => $this->build->getObjectID(),
				'fk_comment' => $newComment->getObjectID(),
			],
		]);
		$notificationAction->executeAction();

		return Core::getTPL()->render('comment', [
			'comment' => $newComment,
		]);
	}

	public function validateLoadMore() {
		if ( empty($this->parameters['buildID']) ) {
			throw new UserInputException('buildID');
		}

		if ( empty($this->parameters['lastID']) ) {
			throw new UserInputException('lastID');
		}

		$build = new Build($this->parameters['buildID']);
		if ( !$build->getObjectID() ) {
			throw new UserInputException('buildID');
		}
	}

	public function loadMore() {
		$commentList = new CommentList();
		$commentList->getConditionBuilder()->add('fk_build = ?', [$this->parameters['buildID']]);
		$commentList->getConditionBuilder()->add('id < ?', [$this->parameters['lastID']]);
		$commentList->sqlLimit = Comment::COMMENTS_PER_PAGE + 1;
		$commentList->sqlOrderBy = 'id desc';
		$commentList->readObjects();

		$html = '';
		foreach ( array_slice($commentList->getObjects(), 0, -1) as $i => $comment ) {
			$html .= Core::getTPL()->render('comment', ['comment' => $comment]);
		}

		$lastCommentID = 0;
		/** @var Comment[] $lastComment */
		$lastComment = array_slice($commentList->getObjects(), Comment::COMMENTS_PER_PAGE, 1);
		if ( isset($lastComment[0]) ) {
			$lastCommentID = $lastComment[0]->getObjectID();
		}

		return [
			'html'    => $html,
			'lastID'  => $lastCommentID,
			'hasMore' => $commentList->count() > Comment::COMMENTS_PER_PAGE,
		];
	}
}