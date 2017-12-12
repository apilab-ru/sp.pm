<?php

namespace app\model;

class log extends base
{
    public function getList()
    {
        return $this->db->select("select * from log order by id DESC limit 30");
    }
    
    public function clearTable()
    {
        $this->db->query("DELETE from log");
    }
}
