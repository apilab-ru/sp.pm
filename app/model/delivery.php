<?php

namespace app\model;

class delivery extends base
{
    public function getList()
    {
        return $this->db->select("SELECT * from delivers");
    }
    
    public function getDataList()
    {
        $page = 1;
        
        $list  = $this->db->select("SELECT SQL_CALC_FOUND_ROWS * from delivers");
        $count = $this->getCount();
        
        return [
            'list'  => $list,
            'count' => $count,
            'page'  => $page
        ];
    }
    
    public function getDelevery($id)
    {
        $id = intval($id);
        return $this->db->selectRow("select * from delivers where id=?d",$id);
    }
}

