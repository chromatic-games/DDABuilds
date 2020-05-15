<?php

use system\Core;

abstract class DataObject
{
    protected $id;
    protected $tablename;
    protected $data = array();

    /**
     * @param int $setID
     */
    public function setID($setID)
    {
        $this->id = $setID;
    }

    /**
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getData($key)
    {
        if (!array_key_exists($key, $this->data)) {
            throw new InvalidArgumentException();
        }
        return $this->data[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return boolean
     */
    public function load()
    {
        $query = '
            SELECT
                *
            FROM
                `' . $this->tablename . '`
            WHERE
                `id` = ?
            ';
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($this->id));
        $row = $cmd->fetch(PDO::FETCH_ASSOC);
        if ($row == false) {
            return false;
        }

        $this->data = $row;
        return true;
    }

    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->getID()) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    /**
     * @return bool
     */
    private function insert()
    {
        $question = array_map(
            function () {
                return '?';
            },
            $this->data
        );
        $implode = implode(',', $question);
        $query = '
            INSERT INTO
                `' . $this->tablename . '` (' . implode(',', array_keys($this->data)) . ')
            VALUES
                (' . $implode . ')
        ';

        $cmd = Core::getDB()->prepareStatement($query);
	    $cmd->execute(array_values($this->data));

        return true;
    }

    /**
     * @return bool
     */
    private function update()
    {
        foreach ($this->data as $key => $value) {
            $set[] = $key . '=?';
        }

        $query = '
            UPDATE
                `' . $this->tablename . '`
            SET
                ' . implode(',', $set) . '
            WHERE
                id=' . $this->getID() . '
        ';

        $cmd = Core::getDB()->prepareStatement($query);
	    $cmd->execute(array_values($this->data));

        return true;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $query = '
            DELETE FROM ' . $this->tablename . '
            WHERE id = ?';

        $cmd = Core::getDB()->prepareStatement($query);
	    $cmd->execute(array($this->getID()));

        return true;
    }
}
