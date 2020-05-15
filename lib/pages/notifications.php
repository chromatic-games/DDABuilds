<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 27.04.2017
 * Time: 03:01
 */

require_once 'config.php';
include "header.php";

if (empty($steamprofile['steamid'])) {
    http_response_code(404);
    exit('Not logged into steam');
}
?>

<body>
<?php
include "navbar.php";
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            $notifications = Notifications::getNotificationsForUser($steamprofile['steamid'], $oDBH);
            foreach ($notifications as $notification) {
                include 'notifications/notification.php';
            }
            Notifications::markAllNotificationsAsRead($steamprofile['steamid'], $oDBH)
            ?>
        </div>
    </div>
</div>