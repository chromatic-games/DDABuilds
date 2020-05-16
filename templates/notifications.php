<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 27.04.2017
 * Time: 03:01
 */

use system\Core;

if (!Core::getUser()->steamID) {
    http_response_code(404);
    exit('Not logged into steam');
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            $notifications = Notifications::getNotificationsForUser(Core::getUser()->steamID);
            if ( $notifications ) {
	            foreach ($notifications as $notification) {
		            include LIB_DIR.'notifications/notification.php';
	            }

	            Notifications::markAllNotificationsAsRead(Core::getUser()->steamID);
            }
            else {
            	echo 'No notifications';
            }
            ?>
        </div>
    </div>
</div>