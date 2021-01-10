<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/1/8
 * Time: 20:32
 */

class Db
{
    private $con;
    private $res;

    // 关闭数据库连接
    private function close()
    {
        if (!is_bool($this->res)) {
            mysqli_free_result($this->res);
        }
        mysqli_close($this->con);
    }

    public function __construct()
    {
        $database = require_once "../config/database.php";
        $this->con = mysqli_connect(
                                $database['host'],
                                $database['username'],
                                $database['password'],
                                $database['database'],
                                $database['port']
        );
        if (!$this->con) {
            die(mysqli_connect_error());
        }
    }

    /**
     * @param array $field_arr
     * @return string               FIELD
     */
    private function _field($field_arr = [])
    {
        if (empty($field_arr)) {
            $field = "*";
        } else {
            $field = "";
            foreach ($field_arr as $v) {
                $field .= "`" . $v . "`,";
            }
            $field = rtrim($field, ",");
        }
        return $field;
    }

    /**
     * @param array $param
     * @return string           WHERE
     */
    private function _where($param = [])
    {
        $where = " where 1=1 ";
        foreach ($param as $k => $v) {
            $v_arr = explode(" ", trim($v));
            $op = count($v_arr) == 2 ? $v_arr[0] : "=";
            $item = $v_arr[count($v_arr) - 1];
            $where .= " and " . $k . $op . $item;
        }
        return $where;
    }

    /**
     * @param $table                表名
     * @param array $param          参数
     * @param array $field_arr      返回字段
     * @param int $type             返回结果 { MYSQLI_ASSOC: 关联数组  MYSQLI_NUM：索引数组 }
     * @return array|null
     */
    private function _one($table, $param = [], $field_arr = [], $type=MYSQLI_NUM)
    {
        $records = null;
        if ($this->res = mysqli_query($this->con, "SELECT " . $this->_field($field_arr) . " FROM " . $table . $this->_where($param) . " LIMIT 1")) {
            $records = mysqli_fetch_array($this->res, $type);
            $this->close();
        }
        return $records;
    }

    private function _all($table, $param = [], $field_arr = [], $type = MYSQLI_NUM)
    {
        $this->res = mysqli_query($this->con, "SELECT {$this->_field($field_arr)} FROM $table {$this->_where($param)}");
        $records = null;
        if ($this->res) {
            $records = mysqli_fetch_all($this->res, $type);
            $this->close();
        }
        return $records;
    }

    private function _page($table, $index, $size, $param = [], $field_arr = [], $type = MYSQLI_NUM)
    {
        $ans = array();
        // data
        $index <= 1 ? $index = 1 : "";
        $recode_index = ($index - 1) * $size;
        $this->res = mysqli_query($this->con, "SELECT {$this->_field($field_arr)} FROM $table {$this->_where($param)} LIMIT $recode_index, $size");
        $recodes = null;
        if ($this->res) {
            $recodes = mysqli_fetch_all($this->res, $type);
            $ans['data'] = $recodes;
        }

        // total page
        $this->res = mysqli_query($this->con, "SELECT COUNT(*) as cnt FROM $table {$this->_where($param)}");
        if ($this->res) {
            $recode_total = mysqli_fetch_array($this->res, MYSQLI_ASSOC)['cnt'];
            $ans['total_page'] = ceil($recode_total / $size);
            $this->close();
        }
        return $ans;
    }

    public function _fetch($sql, $type = "one")
    {
        $records = null;
        if ($this->res = mysqli_query($this->con, $sql)) {
            if ($type == "one") {
                $records = mysqli_fetch_array($this->res, MYSQLI_ASSOC);
            } else {
                $records = mysqli_fetch_all($this->res, MYSQLI_ASSOC);
            }
            $this->close();
        }
        return $records;
    }

    /**
     * @param $table
     * @param array $param
     * @param array $field_arr
     * @return array|null           返回： 字段-值 的关联数组
     */
    public function one($table, $param = [], $field_arr = [])
    {
        return $this->_one($table, $param, $field_arr, MYSQLI_ASSOC);
    }

    /**
     * @param $table
     * @param array $param
     * @param array $field_arr
     * @return array|null           返回： 索引-值 的索引数组
     */
    public function one_num($table, $param = [], $field_arr = [])
    {
        return $this->_one($table, $param, $field_arr, MYSQLI_NUM);
    }

    /**
     * @param $table
     * @param array $param                插入的数据，以关联数组的形式
     * @return bool|int|string
     */
    public function insert($table, $param)
    {
        $fields = "";
        $values = "";
        foreach ($param as $k => $v) {
            $fields .= $k . ",";
            $values .= "'" . $v . "'" . ",";
        }
        $fields = rtrim($fields, ",");
        $values = rtrim($values, ",");
        $this->res = mysqli_query($this->con, "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")");
        $insert_id = false;
        if ($this->res) {
            $insert_id = mysqli_insert_id($this->con);
        }
        $insert_id == false ? "" : $this->close();
        return $insert_id;
    }

    /**
     * @param $table
     * @param array $param                  关联数组，条件
     * @return bool|int                     删除成功返回删除的记录行数
     */
    public function delete($table, $param)
    {
        $where = " WHERE ";
        foreach ($param as $k => $v) {
            $where .= $k . "='" . $v . "' and";
        }
        $where = rtrim($where, "and");
        $this->res = mysqli_query($this->con, "DELETE FROM " . $table . $where);
        $delete_num = false;
        if ($this->res) {
            $delete_num = mysqli_affected_rows($this->con);
            $this->close();
        }
        return $delete_num;
    }

    /**
     * @param $table
     * @param array $data               数据
     * @param array $param              条件
     * @return bool|int                 修改成功的个数
     */
    public function update($table, $data, $param)
    {
        $set = " SET ";
        $where = " WHERE ";
        foreach ($data as $k => $v) {
            $set .= " $k='$v',";
        }
        foreach ($param as $k => $v) {
            $where .= " $k='$v' and";
        }
        $set = rtrim($set, ",");
        $where = rtrim($where, "and");
        $this->res = mysqli_query($this->con, "UPDATE $table $set $where");
        $update_num = false;
        if ($this->res) {
            $update_num = mysqli_affected_rows($this->con);
        }
        return $update_num;
    }

    public function all($table, $param = [], $field_arr = [])
    {
        return $this->_all($table, $param, $field_arr, MYSQLI_ASSOC);
    }

    public function all_num($table, $param = [], $field_arr = [])
    {
        return $this->_all($table, $param, $field_arr, MYSQLI_NUM);
    }

    public function page($table, $param = [], $field_arr = [], $index = 1, $size = 20)
    {
        return $this->_page($table, $index, $size, $param, $field_arr, MYSQLI_ASSOC);
    }

    public function page_num($table, $param = [], $field_arr = [], $index = 1, $size = 2)
    {
        return $this->_page($table, $index, $size, $param, $field_arr, MYSQLI_NUM);
    }

    public function fetch($sql)
    {
        return $this->_fetch($sql, "one");
    }

    public function fetch_all($sql)
    {
        return $this->_fetch($sql, "all");
    }
}
