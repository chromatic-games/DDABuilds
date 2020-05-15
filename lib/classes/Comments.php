<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 21.04.2017
 * Time: 17:37
 */
class Comments
{
    public static function getBuildFromComment($commentID)
    {
        $comment = new Comment();
        $comment->setID($commentID);
        if ($comment->load()) {
            return $comment->getData('fk_build');
        }
        return false;
    }

    public static function getCommentOwner($commentID)
    {
        $comment = new Comment();
        $comment->setID($commentID);
        if ($comment->load()) {
            return $comment->getData('steamid');
        }
        return false;
    }

    /**
     * @param int $userID
     * @param PDO $oDBH
     * @return array $comments
     */
    public static function getAllCommentsForUser($userID, $oDBH)
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                comments
            WHERE
                steamid = ?
            ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($userID));
        $comments = array();
        while ($row = $cmd->fetch()) {
            $comment = new Comment();
            $comment->setID($row['id']);
            if (!$comment->load()) {
                continue;
            }
            $comments[] = $comment;
        }
        return $comments;
    }

    /**
     * @param int $buildID
     * @param PDO $oDBH
     * @return array $comments
     */
    public static function getAllCommentsForBuild($buildID, $oDBH)
    {

        $query = sprintf('
            SELECT
                c.*, IFNULL(SUM(CASE WHEN commentvotes.vote = 1 THEN 1 ELSE 0 END), 0) AS positivevotes, IFNULL(SUM(CASE WHEN commentvotes.vote = -1 THEN -1 ELSE 0 END), 0) AS negativevotes 
            FROM 
                comments as c
            LEFT JOIN
                commentvotes ON commentvotes.fk_comment = c.id
            WHERE
                fk_build=?
            GROUP BY
                c.id
             ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($buildID));
        $comments = array();
        while ($row = $cmd->fetch()) {
            $comment = new Comment();
            $comment->setID($row['id']);
            if (!$comment->load()) {
                continue;
            }
            $comment->setData('positivevotes', $row['positivevotes']);
            $comment->setData('negativevotes', $row['negativevotes']);
            $comments[] = $comment;
        }
        return $comments;
    }

    public static function getAllUserInvolvedInBuildExcept($buildID, $exceptID, $oDBH)
    {
        $build = new Build();
        $build->setID($buildID);
        $build->load();
        $buildSteamID = $build->getData('fk_user');

        $query = sprintf('
            SELECT
                steamid
            FROM 
                comments
            WHERE
                fk_build = ? AND steamid != ? AND steamid != ?
            GROUP BY steamid
            ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($buildID, $buildSteamID, $exceptID));
        $users = array();
        while ($row = $cmd->fetch()) {
            $users[] = $row['steamid'];
        }
        return $users;
    }
}