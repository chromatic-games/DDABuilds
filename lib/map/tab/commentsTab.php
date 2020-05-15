<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 20.04.2017
 * Time: 13:15
 */

if (isset($steamprofile['steamid']))
    $votedOptions = CommentVotes::getUserCommentVotesForBuild($build->getID(), $steamprofile['steamid'], $oDBH);

foreach ($comments as $comment) {
    $up = '';
    $down = '';
    if (isset($steamprofile['steamid']) && $votedOptions) {
        foreach ($votedOptions as $key => $vote) {
            if ($comment->getID() == $key) {
                if ($vote == 1) {
                    $up = 'disabledlink';
                } else if ($vote == -1){
                    $down = 'disabledlink';
                }
            }
        }
    }
    include 'comment.php';
}

if (isset($_SESSION['steamid'])) {
    include 'commentBox.php';
}