<?php

namespace app\model;

class Base
{
    public function __construct()
    {
        $this->db = \app\app::$db;
    }
    
    public function updateobject($table,$send)
    {
        if ($send['id']) {
            $id = $send['id'];
            unset($send['id']);
            $this->db->update($table, $send, $id);
        } else {
            $id = $this->db->insert($table, $send);
        }
        return $id;
    }
    
    public function deleteObject($table,$id)
    {
        $this->db->query("DELETE from $table where id=?d",$id);
    }
    
    public function createLimit(&$page,&$limit)
    {
        $limit = ($limit) ? $limit : 20;
        $page  = ($page)  ? $page  : 1;
        $start = ($page-1) * $limit;
        return " limit $start,$limit";
    }
    
    public function createOrder($order,$orderType,$defaultOrder="id",$defaultOrderType='DESC')
    {
        $order = $order ? $order : $defaultOrder;
        $orderType = ($orderType) ? $orderType : $defaultOrderType;
        if($orderType == 'DESC'){
            $orderType = 'DESC';
        }else{
            $orderType = 'ASC';
        }
        return " order by $order $orderType";
    }
    
    public function getCount()
    {
        return $this->db->selectCell("SELECT FOUND_ROWS()");
    }
    
    public function setFilter($base, $param)
    {
        foreach($param as $key=>$item){
            $base[$key] = $item;
        }
        return $base;
    }
}