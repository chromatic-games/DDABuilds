<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 05.06.2017
 * Time: 23:53
 */
class ThumbnailThread extends Thread
{
    private $build;

    /**
     * @return Build
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @param Build $build
     */
    public function setBuild($build)
    {
        $this->build = $build;
    }

    public function run(){
        $build = $this->build;
        $build->setData('thumbnail', file_get_contents(BASE_URL."/getthumbnail.php?build=" . $build->getID()));
        $build->save();
    }
}