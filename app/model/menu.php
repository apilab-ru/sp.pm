<?php

namespace app\model;

class menu extends base
{
    private static $list    = null;
    private static $current = null;
    
    public function __construct($struct="") 
    {
        parent::__construct();
        if(!$this->list){
            $this->list = $this->db->select("SELECT * from struct where parent=0 order by weight ASC");
            foreach($this->list as $key=>$item){
                if($item['link'] == $struct){
                    $this->list[$key]['checked'] = true;
                    $this->current = $item;
                }
            }
        }
    }
    
    public function getList()
    {
        return $this->list;
    }
    
    public function getTitle()
    {
        return $this->current['title'];
    }
    
    public function getId()
    {
        return $this->current['id'];
    }
    
    public function getDescription() {
        return $this->current['description'];
    }

}
