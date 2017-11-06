<?php

namespace app\controller;

class photoCache {
    
    public function main($arg)
    {
        $file = explode("_", $arg);
        $set  = explode(".",$file[1]);
        $ext  = $set[1];
        $images = new \app\model\images();
        
        $images->createCache($arg);
    }
    
    public function instagram($arg, $send, $args)
    {
        unset($args[0]);
        unset($args[1]);
        $ext = explode(".", $args[5])[1];
        if($ext == 'png'){
            $type = 'image/png';
        }else{
            $type = 'image/jpeg';
        }
        
        header('Content-Type: ' . $type);
        echo file_get_contents("https://scontent.cdninstagram.com/" . implode("/", $args));
    }
    
}
