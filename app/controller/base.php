<?php

namespace app\controller;

class base {
    
    public function render($tpl,$args=null)
    {
        $sm = $this->initRender();
        if($args){
            $sm->assign($args);
        }
        $tpl = "app:" . $tpl;
        return $sm->fetch($tpl);
    }
    
    public function initRender()
    {
        require_once $_SERVER['DOCUMENT_ROOT']."/vendor/smarty/setup.php";
        return initSmarty();
    }
    
    public function template($tpl)
    {
        return file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/view/" . $tpl . ".html");
    }
    
    public function main()
    {
        echo " test ";
    }
    
}
