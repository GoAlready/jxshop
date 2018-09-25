<?php

    // 定义常量

    define('ROOT', __DIR__ . '/../');

    // 引入函数文件
    require(ROOT.'libs/functions.php');

    // 类的自动加载
    function load($class)
    {
        $path = str_replace('\\','/',$class);

        require(ROOT.$path.'.php');
    }

    spl_autoload_register('load');

    // 获取类名、方法名
    $controller = '\controllers\IndexController';
    $action = 'index';

    if(isset($_SERVER['PATH_INFO']))
    {
        // 转换成数组
        $route = explode('/',$_SERVER['PATH_INFO']);

        $controller = '\controllers\\'.ucfirst($route[1]).'Controller';

        $action = $route[2];
    }

    $c = new $controller;
    $c->$action();

?>