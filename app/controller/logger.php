<?php

namespace app\controller;

class logger extends base{
    
    public function log()
    {
        $list = (new \app\model\log())->getList();
        
        echo $this->render('log/list',[
            'list'=>$list
        ]);
    }
    
}
