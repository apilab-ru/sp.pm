<?php

namespace app\model;

class providers extends base
{
    public $providersFilter = [
        'limit'      => 10,
        'page'       => 1,
        'order'      => 'p.id',
        'order_type' => 'DESC'
    ];
    
    public function providersData($param)
    {
        $sql = "SELECT SQL_CALC_FOUND_ROWS * from providers as p ";
        
        $filter = $this->setFilter($this->providersFilter, $param);
        
        $page  = intval($filter['page']);
        $limit = intval($filter['limit']);
        
        $sql .= $this->createOrder($filter['order'], $filter['order_type']);
        $sql .= $this->createLimit($page, $limit);
        
        $list = $this->db->select($sql);
        
        $count = $this->getCount();
        
        return [
            'list'  => $list,
            'count' => $count,
            'page'  => $page,
            'limit' => $limit
        ];
    }
    
    public function getProvider($id)
    {
        $id = intval($id);
        return $this->db->selectRow("select * from providers where id=?d",$id);
    }
    
    public function saveProvider($form)
    {
        return $this->updateObject('providers', $form);
    }
    
    public function delete($id)
    {
        return $this->deleteObject('providers', $id);
    }
}

