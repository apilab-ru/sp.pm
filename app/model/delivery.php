<?php

namespace app\model;

class delivery extends base
{
    public function getList()
    {
        return $this->db->select("SELECT * from delivers");
    }
    
    public function getListDelivery()
    {
        $list = $this->getList();
        foreach($list as $key=>$item){
            $list[$key]['point'] = $this->parsePoint($item['point']);
        }
        return $list;
    }
    
    public function parsePoint($point)
    {
        $mas = explode(",",$point);
        return [
            (float)trim($mas[0]),
            (float)trim($mas[1])
        ];
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
    
    public function saveDelivery($form)
    {
        if(!$form['address'] || !$form['point']){
            throw new \Exception("Верно заполните адрес!");
        }
        return $this->updateObject('delivers', $form);
    }
}

