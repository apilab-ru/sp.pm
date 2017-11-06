<?php

namespace app\model;

class users extends base
{
    public function getList()
    {
        return $this->db->select("select * from users");
    }
    
    public function getUser($id=null)
    {
        if(!$id){
            $id = $_SESSION['user']['id'];
        }
        $user = $this->db->selectRow("select * from users where id=?d", $id);
        $user['photo'] = $this->db->selectRow("select * from images where parent='user' && parent_id=?d", $id);
        return $user;
    }
}
