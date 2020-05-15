<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 27.04.2017
 * Time: 03:01
 */

if (empty($steamprofile['steamid'])) {
    http_response_code(404);
    exit('Not logged into steam');
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            $notifications = Notifications::getNotificationsForUser($steamprofile['steamid']);
            foreach ($notifications as $notification) {
                include LIB_DIR.'notifications/notification.php';
            }
            Notifications::markAllNotificationsAsRead($steamprofile['steamid'])
            ?>
        </div>
    </div>
</div>