<?php

use system\Core;

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 21.04.2017
 * Time: 18:02
 */
class CommentVotes
{
	/**
	 * @param int $commentID
	 *
	 * @return array $votes
	 */
    public static function getAllCommentVotesForComment($commentID)
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                commentvotes
            WHERE
                fk_comment = ?
            ');
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($commentID));
        $votes = array();
        while ($row = $cmd->fetch()) {
            $commentvote = new CommentVote();
            $commentvote->setID($row['id']);
            if (!$commentvote->load()) {
                continue;
            }
            $votes[] = $commentvote;
        }
        return $votes;
    }

    public static function getCommentVoting($commentID)
    {
        $voteNumbers = array();
        $voteNumbers['upvotes'] = 0;
        $voteNumbers['downvotes'] = 0;
        $votes = self::getAllCommentVotesForComment($commentID);

        foreach ($votes as $vote) {
            if ($vote->getData('vote') == 1) {
                $voteNumbers['upvotes']++;
            } else {
                $voteNumbers['downvotes']--;
            }
        }

        return $voteNumbers;
    }

    /**
     * @param int $buildid
     * @param int $steamid
     * @return array|bool
     */
    public static function getUserCommentVotesForBuild($buildid, $steamid)
    {
        $query = sprintf('
            SELECT
                c.*
            FROM 
                commentvotes as c
            LEFT JOIN
                comments ON comments.id = c.fk_comment
            WHERE
                c.steamid=? AND comments.fk_build = ?
            GROUP BY
                c.id
             ');
        $cmd = Core::getDB()->prepareStatement($query);
	    $cmd->execute([$steamid, $buildid]);
	    $votes = false;
        while ($row = $cmd->fetch()) {
            $votes[$row['fk_comment']] = $row['vote'];
        }
        return $votes;
    }

    /**
     * @param int $commentid
     * @param int $steamid
     * @return array|bool
     */
    public static function userAlreadyVoted($commentid, $steamid)
    {
        $query = sprintf('
            SELECT
		        id
	        FROM
		        commentvotes
	        WHERE
		        fk_comment = ? AND steamid = ?

            ');
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($commentid, $steamid));
        if ($row = $cmd->fetch()) {
            return $row['id'];
        }
        return false;
    }
}