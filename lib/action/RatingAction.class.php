<?php

namespace action;

use Build;
use Builds;
use Comments;
use CommentVote;
use CommentVotes;
use Notification;
use system\Core;
use Vote;
use Votes;

class RatingAction extends AbstractAction {
	public $loginRequired = true;

	public function execute() {
		parent::execute();

		if ( empty($_POST) || empty($_POST['rating']) || $_POST['rating'] < -1 || $_POST['rating'] > 1 ) {
			throw new \Exception('named exception - no rating given');
		}

		if ( empty($_POST['buildid']) && empty($_POST['commentid']) ) {
			throw new \Exception('invalid build and comment id');
		}

		//Vote process for builds
		if ( !empty($_POST['buildid']) ) {
			$build = new Build();
			$build->setID($_POST['buildid']);
			if ( !$build->load() ) {
				http_response_code(404);
				exit();
			}

			if ( Builds::getBuildOwner($_POST['buildid']) == Core::getUser()->steamID ) {
				throw new \Exception('named exception - cant like own builds');
			}

			if ( $voteid = Votes::userAlreadyVoted($_POST['buildid'], Core::getUser()->steamID) ) {
				$vote = new Vote();
				$vote->setID($voteid);
				$vote->load();
				if ( $vote->getData('vote') == $_POST['rating'] ) {
					http_response_code(404);
					exit();
				}
				$vote->setData('vote', $_POST['rating']);
				$vote->save();
				notificateUser(Builds::getBuildOwner($_POST['buildid']), $_POST['rating'], 2, $_POST['buildid']);
				exit('Success');
			}

			$vote = new Vote();
			$vote->setData('steamid', Core::getUser()->steamID);
			$vote->setData('fk_build', $_POST['buildid']);
			$vote->setData('vote', $_POST['rating']);
			$vote->save();
			notificateUser(Builds::getBuildOwner($_POST['buildid']), $_POST['rating'], 2, $_POST['buildid']);
			exit('Success');
		}
		//Vote process for comments
		elseif ( !empty($_POST['commentid']) ) {
			$comment = new \Comment();
			$comment->setID($_POST['commentid']);
			if ( !$comment->load() ) {
				http_response_code(404);
				exit();
			}
			if ( Core::getUser()->steamID == Comments::getCommentOwner($_POST['commentid']) ) {
				throw new \Exception('named exception - you cant like own builds');
			}

			if ( $voteid = CommentVotes::userAlreadyVoted($_POST['commentid'], Core::getUser()->steamID) ) {
				$vote = new CommentVote();
				$vote->setID($voteid);
				$vote->load();
				if ( $vote->getData('vote') == $_POST['rating'] ) {
					http_response_code(404);
					exit();
				}
				$vote->setData('vote', $_POST['rating']);
				if ( $vote->save() ) {
					$commentVotes = CommentVotes::getCommentVoting($_POST['commentid']);
					notificateUser(Comments::getCommentOwner($_POST['commentid']), $_POST['rating'], 3, Comments::getBuildFromComment($_POST['commentid']), $_POST['commentid']);
					exit(json_encode($commentVotes));
				}
			}

			$vote = new CommentVote();
			$vote->setData('steamid', Core::getUser()->steamID);
			$vote->setData('fk_comment', $_POST['commentid']);
			$vote->setData('vote', $_POST['rating']);
			if ( $vote->save() ) {
				$commentVotes = CommentVotes::getCommentVoting($_POST['commentid']);
				notificateUser(Comments::getCommentOwner($_POST['commentid']), $_POST['rating'], 3, Comments::getBuildFromComment($_POST['commentid']), $_POST['commentid']);
				exit(json_encode($commentVotes));
			}
		}

		function notificateUser($steamid, $rating, $type, $buildID, $commentID = '') {
			$notification = new Notification();
			$notification->setData('steamid', $steamid);
			$notification->setData('data', $rating);
			$notification->setData('type', $type);
			$notification->setData('fk_build', $buildID);
			if ( $commentID ) {
				$notification->setData('fk_comment', $commentID);
			}
			$notification->save();
		}
	}
}