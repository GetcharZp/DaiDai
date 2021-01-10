<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/1/10
 * Time: 13:05
 */

class View
{
    public static function render($path, $data = "")
    {
        require_once "../app/view/" . $path;
    }
}