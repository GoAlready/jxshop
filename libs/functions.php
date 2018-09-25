<?php
    
    function view($file,$data=[])
    {
        // 解压数组
        extract($data);
        
        require(ROOT.'views/'.$file.'.html');
    }

    
?>