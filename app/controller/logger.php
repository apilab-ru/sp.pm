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
    
    public function clear()
    {
        (new \app\model\log())->clearTable();
    }
    
    public function restore()
    {
        $model = new \app\model\log();
        $list = $model->db->select("select * from stock where id<1469");
        pr($list);
    }
    
}
