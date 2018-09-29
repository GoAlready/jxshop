<?php
    
    // 加载视图函数
    function view($file,$data=[])
    {
        // 解压数组
        extract($data);
        
        require(ROOT.'views/'.$file.'.html');
    }

    function redirect($url)
    {
        header('Location:'.$url);
        exit;
    }
    
?>