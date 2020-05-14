<?php

class Builds
{
    const LIMIT = 12;

    /**
     * @param $buildID
     * @return array|bool
     */
    public static function getBuildOwner($buildID)
    {
        $build = new Build();
        $build->setID($buildID);
        if ($build->load()) {
            return $build->getData('fk_user');
        }
        return false;
    }

    /**
     * @param PDO $oDBH
     * @return array $builds
     */
    public static function getAllBuilds($oDBH)
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                builds
            WHERE
                deleted = 0
            ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute();
        $builds = array();
        while ($row = $cmd->fetch()) {
            $build = new Build();
            $build->setID($row['id']);
            if (!$build->load()) {
                continue;
            }
            $builds[] = $build;
        }
        return $builds;
    }

    /**
     * @param int $userID
     * @param PDO $oDBH
     * @return array $builds
     */
    public static function getAllBuildsForUser($userID, $oDBH)
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                builds
            WHERE
                fk_user = ? AND deleted = 0
            ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($userID));
        $builds = array();
        while ($row = $cmd->fetch()) {
            $build = new Build();
            $build->setID($row['id']);
            if (!$build->load()) {
                continue;
            }
            $builds[] = $build;
        }
        return $builds;
    }

    /**
     * @param $sort
     * @param $pageNumber
     * @param $oDBH
     * @param string $order
     * @param string $by
     * @return array
     */
    public static function getBuildsFor($sort, $pageNumber, $oDBH, $order = 'ASC', $by = 'id')
    {
        $values = array();
        $set = array();
        $limitcode = '';

        if ($sort) {
            foreach ($sort as $key => $value) {
                if ($key == 'name' || $key == 'author') {
                    $set[] = 'b.' . $key . ' LIKE ? AND';
                    $values[] = '%' . $value . '%';
                } else if ($key == 'rating') {
                    $set[] = 'b.' . $key . ' >=? AND';
                    $values[] = $value;
                } else {
                    $set[] = 'b.' . $key . ' =? AND';
                    $values[] = $value;
                }
            }
        }

        if ($pageNumber) {
            $limit = self::LIMIT;
            $pageNumber -= 1;
            $startAt = $pageNumber * $limit;
            $values[] = $startAt;
            $values[] = $limit;
            $limitcode = 'LIMIT ? , ?';
        }

        $query = sprintf('
            SELECT
                b.*, maps.name AS mapname, IFNULL(SUM(votes.vote), 0) AS votes
            FROM 
                builds as b
            INNER JOIN
                maps ON maps.id = b.map
            LEFT JOIN
                votes ON votes.fk_build = b.id
            WHERE
                deleted = 0 AND
                ' . implode(' ', $set) . ' 1=1
            GROUP BY
                b.id
            ORDER BY ' . $by . ' ' . $order . '
            ' . $limitcode);
        $oDBH->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $cmd = $oDBH->prepare($query);
        $cmd->execute($values);
        $builds = array();
        while ($row = $cmd->fetch()) {
            $build = new Build();
            $build->setID($row['id']);
            if (!$build->load()) {
                continue;
            }
            $builds[] = $build;
        }
        return $builds;
    }

    /**
     * @param $sort
     * @param $oDBH
     * @return int
     */
    public static function getPageNumbers($sort, $oDBH)
    {
        return intval(ceil(count(self::getBuildsFor($sort, false, $oDBH)) / self::LIMIT));
    }

}