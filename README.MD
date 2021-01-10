# 原生PHP 搭建自己的web框架

## 安装方法
点击`Download ZIP` 或者通过 `Clone` 下载该项目的源码
## 运行方法
方法一：进行项目根目录先使用命令 `php dai run` 运行

方法二：将项目的public目录配置到Nginx、Apache等服务器的虚拟站点下，通过设置相应的域名来访问该项目
## 项目结构
```
-- app
---- controller
---- view
-- config
---- app.php
---- database.php
-- framework
-- public
---- static
---- index.php
dai
README.MD
```

## 相关方法的使用
```php
// 参数，待渲染的数据
View::render($path, $data)


// 表名、条件、查询的字段 查询一条数据以关联数组的形式返回
(new Db())->one($table, $param, $field)
// 查询一条数据以索引数组的形式返回
(new Db())->one_num($table, $param, $field)
// 数据更新
(new Db())->update($table, $data, $param)
// 数据新增
(new Db())->insert($table, $param)
// 查询所有的数据，并以关联数组的形式返回
(new Db())->all($table, $param = [], $field_arr = [])
// 查询所有的数据，并以索引数组的形式返回
(new Db())->all_num($table, $param = [], $field_arr = [])
// 执行自定义的SQL语句并返回一条数据
(new Db())->fetch($sql)
// 执行自定义的SQL语句并返回所有数据
(new Db())->fetch_all($sql)
// 数据删除
(new Db())->delete($table, $param)
// 分页查询
(new Db())->page($table, $param = [], $field_arr = [], $index = 1, $size = 20)

// Session 参数是字符串的时候返回这个字符串对应的session值，参数是数组是将数组的键值存到session中
session()
```

路由地址为 `/控制器名/方法名`

_GET 获取get方式传递的参数

_POST 获取post方式传递的参数


