<?php

use system\Core;

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 27.04.2017
 * Time: 04:40
 */
class Notifications
{
    public static function getNotificationsForUser($steamid)
    {
        $query = sprintf('
            SELECT
                *
            FROM 
                notifications
            WHERE
                steamid = ?
            ORDER BY id DESC
            
            ');
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($steamid));
        $notifications = array();
        while ($row = $cmd->fetch()) {
            $notification = new Notification();
            $notification->setID($row['id']);
            if (!$notification->load()) {
                continue;
            }
            $notifications[] = $notification;
        }
        return $notifications;
    }

    public static function getUnreadNotificationsForUser($steamid)
    {
        $query = sprintf('
            SELECT
                *
            FROM 
                notifications
            WHERE
                steamid = ? AND seen = 0
            ');
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($steamid));
        $notifications = array();
        while ($row = $cmd->fetch()) {
            $notification = new Notification();
            $notification->setID($row['id']);
            if (!$notification->load()) {
                continue;
            }
            $notifications[] = $notification;
        }
        return $notifications;
    }

    public static function markAllNotificationsAsRead($steamid)
    {
        $unreadNotifications = self::getUnreadNotificationsForUser($steamid);
        foreach ($unreadNotifications as $notification) {
            $notification->setData('seen', 1);
            $notification->save();
        }
        return true;
    }

}