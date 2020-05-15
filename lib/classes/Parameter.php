<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 10.03.2017
 * Time: 12:33
 */
class Parameter
{
    /**
     * @param string $key
     * @param bool $required
     * @param string $default
     * @return string
     */
    public static function _POST($key, $required = false, $default = '')
    {
        if (!empty($_POST[$key])) {
            return $_POST[$key];
        }
        if ($required) {
            throw new InvalidArgumentException();
        }
        return $default;
    }

    /**
     * @param string $key
     * @param bool $required
     * @param string $default
     * @return string
     */
    public static function _GET($key, $required = false, $default = '')
    {
        if (!empty($_GET[$key])) {
            return $_GET[$key];
        }
        if ($required) {
            throw new InvalidArgumentException();
        }
        return $default;
    }
}