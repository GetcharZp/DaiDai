<?php
/**
 * User: Dai
 * Date: 2021/1/7 22:30
 */

include_once "../framework/Db/Db.php";
include_once "../framework/View/View.php";
include_once "../framework/Session/Session.php";

$app = require_once "../config/app.php";
if (!$app['app_debug']) {
    error_reporting(0);
}

$path_info = trim($_SERVER['PATH_INFO'], "/");
$query_string = $_SERVER['QUERY_STRING'];

$Controller = explode("/", $path_info)[0];
$Function = explode("/", $path_info)[1];
if ($path_info == "" || $path_info == "index") {
    $Controller = "Index";
    $Function = "home";
}

require_once "../app/controllers/" . $Controller . ".php";
(new $Controller)->$Function();
