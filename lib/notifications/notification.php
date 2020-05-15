<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 27.04.2017
 * Time: 03:11
 */

/**
 * Notification Types:
 * 1 = A user commented your build X
 * 2 = Your build X got up/down voted
 * 3 = Your comment on Build X got up/down voted
 * 4 = User Y also commented on Build X
 */

$data = $notification->getData('data');
$build = new Build();
$build->setID($notification->getData('fk_build'));
$build->load();
$linkToBuild = 'http://' . $_SERVER['HTTP_HOST'] . '/?page=map&load=' . $build->getID();
$buildName = htmlspecialchars($build->getData('name'));

$vote[-1] = 'down';
$vote[1] = 'up';

if ($data == 1) {
    $appearance = 'alert-success';
} else if ($data == -1) {
    $appearance = 'alert-danger';
} else {
    $appearance = 'alert-info';
}

if ($notification->getData('type') == 2) {
    $link = $linkToBuild;
} else {
    $link = $linkToBuild . '&comments#' . $notification->getData('fk_comment');
}

if ($notification->getData('type') == 1) {
    $message = '<u>' . Utility::getSteamName($notification->getData("data")) . '</u> wrote a comment on your build: <u>' . $buildName . '</u>';
} else if ($notification->getData('type') == 2) {
    $message = 'Your build: <u>' . $buildName . '</u> got voted <u>' . $vote[$data] . '</u>';
} else if ($notification->getData('type') == 3) {
    $message = 'Your comment on the build: <u>' . $buildName . '</u> got voted <u>' . $vote[$data] . '</u>';
} else if ($notification->getData('type') == 4) {
    $message = '<u>' . Utility::getSteamName($notification->getData("data")) . '</u> also wrote a comment on the build: <u>' . $buildName . '</u>';
} else {
    $message = 'error';
}

echo '
<div class="alert ' . $appearance . '">
    ' . $notification->getData("date") . ': <a href="' . $link . '" class="alert-link">' . $message . '</a>.
</div>
';