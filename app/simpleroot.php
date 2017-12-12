<?php

namespace app;

class simpleroot{
    
    public function create($controller, $action, $page=false)
    {
        return array(
            'controller' => $controller,
            'action'     => $action,
            'page'       => $page
        );
    }
}