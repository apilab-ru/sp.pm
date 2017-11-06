<?php

function pr() {
    echo "<pre>";
    foreach (func_get_args() as $item) {
        print_r($item);
        echo PHP_EOL;
    }
    echo "</pre>";
}

function dlog($name, $arg=null) {
    
    if(!is_string($arg)){
        $arg = print_r($arg,true);
    }
    
    app\app::$db->insert('log', [
        'name' => $name,
        'log' => $arg
    ]);
}
