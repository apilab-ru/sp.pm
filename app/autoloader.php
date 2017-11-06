<?php

include "app/func.php";

function autoLoader($class){
    
    $class = str_replace("\\", "/", $class);
    if(file_exists($class.".php")){
        include $class.".php";
    }
}

spl_autoload_register('autoLoader');
