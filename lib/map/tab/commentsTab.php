<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 20.04.2017
 * Time: 13:15
 */

use system\Core;

if ( Core::getUser()->steamID ) {
	$votedOptions = CommentVotes::getUserCommentVotesForBuild($build->getID(), Core::getUser()->steamID);
}

foreach ( $comments as $comment ) {
	$up = '';
	$down = '';
	if ( Core::getUser()->steamID && $votedOptions ) {
		foreach ( $votedOptions as $key => $vote ) {
			if ( $comment->getID() == $key ) {
				if ( $vote == 1 ) {
					$up = 'disabledlink';
				}
				elseif ( $vote == -1 ) {
					$down = 'disabledlink';
				}
			}
		}
	}
	include 'comment.php';
}

if ( Core::getUser()->steamID ) {
	include 'commentBox.php';
}