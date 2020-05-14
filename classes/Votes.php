<?php

class Votes
{
    public static function getVotesForBuild($buildID, $oDBH)
    {
        $query = sprintf('
            SELECT
		        *
	        FROM
		        votes
	        WHERE
		        fk_build = ?

            ');
        $cmd = $oDBH->prepare($query);
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

    public static function getVotesForUser($steamid, $oDBH)
    {
        $query = sprintf('
            SELECT
		        *
	        FROM
		        votes
	        WHERE
		        steamid = ?

            ');
        $cmd = $oDBH->prepare($query);
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

    public static function userAlreadyVoted($buildid, $steamid, $oDBH)
    {
        $query = sprintf('
            SELECT
		        id
	        FROM
		        votes
	        WHERE
		        fk_build = ? AND steamid = ?

            ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($buildid, $steamid));
        if ($row = $cmd->fetch()) {
            return $row['id'];
        }


        return false;
    }

    public static function getBuildVoting($buildid, $oDBH)
    {
        $voteNumber = 0;
        $votes = Votes::getVotesForBuild($buildid, $oDBH);

        foreach ($votes as $vote) {
            $voteNumber += $vote->getData('vote');
        }

        return $voteNumber;
    }
}