<?php
/**
 * User: Dai
 * Date: 2021/1/10 15:45
 */

/**
 * @param String|array $data
 * @return mixed
 */
function session($data)
{
    session_start();
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $_SESSION[$k] = $v;
        }
    } else {
        return $_SESSION[$data];
    }
}