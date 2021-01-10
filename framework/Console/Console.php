<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/31
 * Time: 23:12
 */

require_once "Color.php";

class Console
{
    const VERSION = '0.1';

    public function __construct()
    {
        echo  Color::setColor("Dai Version : " . self::VERSION, "green") . "\n";
    }

    private function tips()
    {
        echo "Please Input " . Color::setColor("php dai run" , "deep_green") . " To Run Server \n";
    }

    private function runServer()
    {
        echo "Listening on http://localhost:8080 \n";
        system("php -S localhost:8080 -t ./public");
    }

    public function run()
    {
        $argv = array_slice($GLOBALS['argv'], 1);
        switch (strtolower($argv[0])) {
            case "run":
                $this->runServer();
                break;
            default:
                $this->tips();
                break;
        }
    }
}
