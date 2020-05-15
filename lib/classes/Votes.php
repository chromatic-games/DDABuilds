<?php

use system\Core;

class Votes
{
    public static function getVotesForBuild($buildID)
    {
        $query = sprintf('
            SELECT
		        *
	        FROM
		        votes
	        WHERE
		        fk_build = ?

            ');
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($buildID));
        $votes = array();
        while ($row = $cmd->fetch()) {
            $vote = new Vote();
            $vote->setID($row['id']);
            if (!$vote->load()) {
                continue;
            }
            $votes[] = $vote;
        }
        return $votes;
    }

    public static function getVotesForUser($steamid)
    {
        $query = sprintf('
            SELECT
		        *
	        FROM
		        votes
	        WHERE
		        steamid = ?

            ');
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($steamid));
        $votes = array();
        while ($row = $cmd->fetch()) {
            $vote = new Vote();
            $vote->setID($row['id']);
            if (!$vote->load()) {
                continue;
            }
            $votes[] = $vote;
        }
        return $votes;
    }

    public static function userAlreadyVoted($buildid, $steamid)
    {
        $query = sprintf('
            SELECT
		        id
	        FROM
		        votes
	        WHERE
		        fk_build = ? AND steamid = ?

            ');
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($buildid, $steamid));
        if ($row = $cmd->fetch()) {
            return $row['id'];
        }


        return false;
    }

    public static function getBuildVoting($buildid)
    {
        $voteNumber = 0;
        $votes = Votes::getVotesForBuild($buildid);

        foreach ($votes as $vote) {
            $voteNumber += $vote->getData('vote');
        }

        return $voteNumber;
    }
}