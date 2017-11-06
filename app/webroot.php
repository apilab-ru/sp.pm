<?php

namespace app;

class webroot{
    
    public function create($controller, $action, $cache=false)
    {
        return array(
            'controller' => $controller,
            'action' => $action,
            'cache' => $cache
        );
    }
}