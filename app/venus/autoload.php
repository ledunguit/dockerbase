<?php
function autoload($class) {
    $file = strtolower(str_replace('\\','/',$class).'.php');
    //echo 'Yêu cầu '.$file . '</br>';
    if (file_exists($file)) {
        require_once $file;
        //echo 'Đã xử lí ' .$file .'</br>';
    }
    /* else {
        echo 'Không thể nạp '.$file . '</br>';
    } */
}
spl_autoload_register('autoload');