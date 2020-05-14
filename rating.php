<?php
require 'steamauth/steamauth.php';
require_once 'config.php';

if (!isset($_SESSION['steamid']) ||
    empty($_POST) ||
    empty($_POST['rating']) ||
    $_POST['rating'] < -1 ||
    $_POST['rating'] > 1
) {
    http_response_code(404);
    exit();
}

if (empty($_POST['buildid']) && empty($_POST['commentid'])) {
    http_response_code(404);
    exit();
}

require 'steamauth/userInfo.php';

//Vote process for builds

if (!empty($_POST['buildid'])) {
    $build = new Build();
    $build->setID($_POST['buildid']);
    if (!$build->load()) {
        http_response_code(404);
        exit();
    }

    if (Builds::getBuildOwner($_POST['buildid']) == $steamprofile['steamid']) {
        http_response_code(404);
        exit();
    }

    $oDBH = Database::getInstance();

    if ($voteid = Votes::userAlreadyVoted($_POST['buildid'], $steamprofile['steamid'], $oDBH)) {
        $vote = new Vote();
        $vote->setID($voteid);
        $vote->load();
        if ($vote->getData('vote') == $_POST['rating']) {
            http_response_code(404);
            exit();
        }
        $vote->setData('vote', $_POST['rating']);
        $vote->save();
        notificateUser(Builds::getBuildOwner($_POST['buildid']), $_POST['rating'], 2, $_POST['buildid']);
        exit('Success');
    }

    $vote = new Vote();
    $vote->setData('steamid', $steamprofile['steamid']);
    $vote->setData('fk_build', $_POST['buildid']);
    $vote->setData('vote', $_POST['rating']);
    $vote->save();
    notificateUser(Builds::getBuildOwner($_POST['buildid']), $_POST['rating'], 2, $_POST['buildid']);
    exit('Success');
}

//Vote process for comments

if (!empty($_POST['commentid'])) {
    $comment = new Comment();
    $comment->setID($_POST['commentid']);
    if (!$comment->load()) {
        http_response_code(404);
        exit();
    }
    if ($steamprofile['steamid'] == Comments::getCommentOwner($_POST['commentid'])) {
        http_response_code(404);
        exit();
    }

    $oDBH = Database::getInstance();

    if ($voteid = CommentVotes::userAlreadyVoted($_POST['commentid'], $steamprofile['steamid'], $oDBH)) {
        $vote = new CommentVote();
        $vote->setID($voteid);
        $vote->load();
        if ($vote->getData('vote') == $_POST['rating']) {
            http_response_code(404);
            exit();
        }
        $vote->setData('vote', $_POST['rating']);
        if ($vote->save()) {
            $commentVotes = CommentVotes::getCommentVoting($_POST['commentid'], $oDBH);
            notificateUser(Comments::getCommentOwner($_POST['commentid']), $_POST['rating'], 3, Comments::getBuildFromComment($_POST['commentid']), $_POST['commentid']);
            exit(json_encode($commentVotes));
        }
    }

    $vote = new CommentVote();
    $vote->setData('steamid', $steamprofile['steamid']);
    $vote->setData('fk_comment', $_POST['commentid']);
    $vote->setData('vote', $_POST['rating']);
    if ($vote->save()) {
        $commentVotes = CommentVotes::getCommentVoting($_POST['commentid'], $oDBH);
        notificateUser(Comments::getCommentOwner($_POST['commentid']), $_POST['rating'], 3, Comments::getBuildFromComment($_POST['commentid']), $_POST['commentid']);
        exit(json_encode($commentVotes));
    }
}

function notificateUser($steamid, $rating, $type, $buildID, $commentID = '')
{
    $notification = new Notification();
    $notification->setData('steamid', $steamid);
    $notification->setData('data', $rating);
    $notification->setData('type', $type);
    $notification->setData('fk_build', $buildID);
    if ($commentID) {
        $notification->setData('fk_comment', $commentID);
    }
    $notification->save();
}