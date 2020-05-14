<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 22.04.2017
 * Time: 16:01
 */

require 'steamauth/steamauth.php';
require_once 'config.php';

if (!isset($_SESSION['steamid']) ||
    empty($_POST) ||
    empty($_POST['buildid']) ||
    empty($_POST['comment'])
) {
    http_response_code(404);
    exit();
}
$build = new Build();
$build->setID($_POST['buildid']);
if (!$build->load()) {
    http_response_code(404);
    exit();
}

$oDBH = Database::getInstance();
require 'steamauth/userInfo.php';

$comment = new Comment();
$comment->setData('steamid', $steamprofile['steamid']);
$comment->setData('fk_build', $_POST['buildid']);
$comment->setData('comment', $_POST['comment']);
if ($commentid = $comment->save()) {
    $userInvolved = Comments::getAllUserInvolvedInBuildExcept($_POST['buildid'], $steamprofile['steamid'], $oDBH);
    foreach ($userInvolved as $user) {
        $notification = new Notification();
        $notification->setData('steamid', $user);
        $notification->setData('data', $steamprofile['steamid']);
        $notification->setData('type', 4);
        $notification->setData('fk_build', $_POST['buildid']);
        $notification->setData('fk_comment', $commentid);
        $notification->save();
    }
    if ($steamprofile['steamid'] != Builds::getBuildOwner($_POST['buildid'])) {
        $notification = new Notification();
        $notification->setData('steamid', Builds::getBuildOwner($_POST['buildid']));
        $notification->setData('data', $steamprofile['steamid']);
        $notification->setData('type', 1);
        $notification->setData('fk_build', $_POST['buildid']);
        $notification->setData('fk_comment', $commentid);
        $notification->save();
    }
    exit($commentid);
}