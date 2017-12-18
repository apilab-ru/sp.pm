<?php

namespace app\model;

class events{
    
    public static function event($user, $type, $event=array())
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/cache/" . $user . ".txt";
        $event['type'] = $type;
        $event['time'] = time();
        file_put_contents($file, json_encode($event, JSON_UNESCAPED_UNICODE));
    }
    
    public static function getTimeLastEvent($user)
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/cache/" . $user . ".txt";
        clearstatcache();
        return filemtime($file);
    }
    
    public static function read($user, $time=0)
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/cache/" . $user . ".txt";
        clearstatcache();
        $check = filemtime($file);
        if($check > $time){
            $event = json_decode(file_get_contents($file), 1 );
            return $event;
        }
    }
}

