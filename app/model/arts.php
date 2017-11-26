<?php

namespace app\model;


class arts extends base
{
    public function getArtsStruct($id)
    {
        return $this->db->select("select * from arts where struct=?d && parent=0",$id);
    }
    
    public function getTreeArts($id)
    {
        return $this->db->select("select *,id as ARRAY_KEY,`parent` as PARENT_KEY from arts where struct=?d order by `order`",$id);
    }
    
    public function getArt($id)
    {
        return $this->db->SelectRow("select * from arts where id=?d",$id);
    }
    
    public function deleteArt($id)
    {
        $this->deleteObject('arts', $id);
        $this->db->query("UPDATE arts set parent=0 where parent=?d",$id);
    }
    
    public function updateArtsTree($list)
    {
        return $this->updateTree('arts',$list);
    }
}
