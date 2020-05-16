<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 22.04.2017
 * Time: 16:01
 */

namespace action;

use Build;
use Builds;
use Comment;
use Comments;
use Notification;
use system\Core;
use system\exception\IllegalLinkException;

class CommentAction extends AjaxAction {
	public $loginRequired = true;

	public $buildID = 0;

	/** @var Build */
	public $build;

	/**
	 * @throws IllegalLinkException
	 */
	public function readParameters() {
		parent::readParameters();

		if ( empty($_POST['buildid']) || empty($_POST['comment']) ) {
			throw new IllegalLinkException();
		}

		$this->buildID = (int) $_POST['buildid'];
		if ( !$this->buildID ) {
			throw new IllegalLinkException();
		}

		$this->build = new Build();
		$this->build->setID($this->buildID);
		if ( !$this->build->load() ) {
			throw new IllegalLinkException();
		}
	}

	public function execute() {
		parent::execute();

		$comment = new Comment();
		$comment->setData('steamid', Core::getUser()->steamID);
		$comment->setData('fk_build', $this->buildID);
		$comment->setData('comment', $_POST['comment']);
		if ( $commentid = $comment->save() ) {
			$userInvolved = Comments::getAllUserInvolvedInBuildExcept($this->buildID, Core::getUser()->steamID);
			foreach ( $userInvolved as $user ) {
				$notification = new Notification();
				$notification->setData('steamid', $user);
				$notification->setData('data', Core::getUser()->steamID);
				$notification->setData('type', 4);
				$notification->setData('fk_build', $this->buildID);
				$notification->setData('fk_comment', $commentid);
				$notification->save();
			}
			if ( Core::getUser()->steamID != Builds::getBuildOwner($this->buildID) ) {
				$notification = new Notification();
				$notification->setData('steamid', Builds::getBuildOwner($this->buildID));
				$notification->setData('data', Core::getUser()->steamID);
				$notification->setData('type', 1);
				$notification->setData('fk_build', $this->buildID);
				$notification->setData('fk_comment', $commentid);
				$notification->save();
			}
			exit($commentid);
		}
	}
}