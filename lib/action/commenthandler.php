<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 22.04.2017
 * Time: 16:01
 */

use system\Core;

if ( !Core::getUser()->steamID || empty($_POST) || empty($_POST['buildid']) || empty($_POST['comment']) ) {
    http_response_code(404);
    exit();
}

$build = new Build();
$build->setID($_POST['buildid']);
if (!$build->load()) {
    http_response_code(404);
    exit();
}

$comment = new Comment();
$comment->setData('steamid', Core::getUser()->steamID);
$comment->setData('fk_build', $_POST['buildid']);
$comment->setData('comment', $_POST['comment']);
if ($commentid = $comment->save()) {
    $userInvolved = Comments::getAllUserInvolvedInBuildExcept($_POST['buildid'], Core::getUser()->steamID);
    foreach ($userInvolved as $user) {
        $notification = new Notification();
        $notification->setData('steamid', $user);
        $notification->setData('data', Core::getUser()->steamID);
        $notification->setData('type', 4);
        $notification->setData('fk_build', $_POST['buildid']);
        $notification->setData('fk_comment', $commentid);
        $notification->save();
    }
    if (Core::getUser()->steamID != Builds::getBuildOwner($_POST['buildid'])) {
        $notification = new Notification();
        $notification->setData('steamid', Builds::getBuildOwner($_POST['buildid']));
        $notification->setData('data', Core::getUser()->steamID);
        $notification->setData('type', 1);
        $notification->setData('fk_build', $_POST['buildid']);
        $notification->setData('fk_comment', $commentid);
        $notification->save();
    }
    exit($commentid);
}