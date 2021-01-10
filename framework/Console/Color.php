<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/31
 * Time: 23:31
 */

class Color
{
    const FONTCOLOR = [
        'black' => '30',
        'red' => '31',
        'green' => '32',
        'yellow' => '33',
        'blue' => '34',
        'purple' => '35',
        'deep_green' => '36',
        'write' => '37',
    ];
    public static function setColor($text, $color)
    {
        return "\033[" . self::FONTCOLOR[$color] . "m" . $text . "\033[0m";
    }
}