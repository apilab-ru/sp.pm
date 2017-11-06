<?php

namespace app\model;


class arts extends base
{
    public function getArtsStruct($id)
    {
        return $this->db->select("select * from arts where struct=?d",$id);
    }
}
