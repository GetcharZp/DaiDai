<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/1/7
 * Time: 23:54
 */


class Index
{
    public function home()
    {
        View::render("index/index.php");
    }

    public function postOne()
    {
        $db = new Db();
        var_dump($db->one("test", ['id' => '2'], ['name']));
    }

    public function postInsert()
    {
        $db = new Db();
        var_dump($db->insert("test", ["name" => $_POST['name']]));
    }

    public function postDelete()
    {
        $db = new Db();
        var_dump($db->delete("test", ['name' => $_POST['name']]));
    }

    public function postUpdate()
    {
        $db = new Db();
        var_dump($db->update("test", ['name' => $_POST['name']], ['name' => $_GET['name']]));
    }

    public function postAll()
    {
        $db = new Db();
        var_dump($db->all("test"));
    }

    public function postPage()
    {
        $db = new Db();
        var_dump($db->page("test", [], [], 0, 3));
    }

    public function postFetch()
    {
        $db = new Db();
        var_dump($db->fetch_all("select `name` from test"));
    }

    public function getIndex()
    {
        $data = ["name" => "Getcharzp"];
        View::render("index.php", $data);
    }

    public function getJson()
    {
        $data = ["name" => "1234", "id" => "1"];
        exit(json_encode($data));
    }

    public function login()
    {
        session(["name" => "GetcharZp"]);
    }

    public function sessionTest()
    {
        var_dump(session("name"));
    }
}