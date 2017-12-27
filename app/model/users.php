<?php

namespace app\model;
use \app\controller\notice;

class users extends base
{
    private $users = [
        "sort"     => "id",
        "sortType" => "DESC",
        "page"     => 1,
        "limit"    => 10
    ];
    
    public function getList($send)
    {
        $filter = $this->setFilter($this->users, $send);
        $sql = " select SQL_CALC_FOUND_ROWS "
                . " u.*, img.name as img_name, img.folder as img_folder, img.type as img_type "
                . " from users as u "
                . " left join images as img on img.parent = 'user' && img.parent_id=u.id ";
        
        $sql .= $this->createOrder($filter['sort'], null, $filter['sortType']);

        $sql .= $this->createLimit($filter['page'], $filter['limit']);
        
        $list = $this->db->select($sql);
        
        foreach($list as $key=>$item){
            if($item['img_name']){
                $list[$key]['image'] = [
                    'name'   => $item['img_name'],
                    'folder' => $item['img_folder'],
                    'type'   => $item['img_type']
                ];
            }
        }
        
        $count = $this->getCount();
        
        return [
            "list"  => $list,
            "count" => $count,
            "page"  => $filter['page'],
            "limit" => $filter['limit']
        ];
    }
    
    public function getSimpleList()
    {
        return $this->db->select("SELECT id, surname, name, secondname from users where id!=0"); //&& type='simple'
    }
    
    public function getUser($id=null)
    {
        if(!$id && $id!=="0" && $id!==0){
            $id = $_SESSION['user']['id'];
        }
        $user = $this->db->selectRow("select * from users where id=?d", $id);
        
        $user['photo'] = $this->db->selectRow("select * from images where parent='user' && parent_id=?d", $id);
        return $user;
    }
    
    public function deleteUser($id)
    {
        $this->deleteObject('users', $id);
        $file = $this->db->selectRow("select * from images where parent='user' && parent_id=?d", $id);
        if($file){
            $images = new \app\model\images();
            $images->remove($file);
        }
    }
    
    public $user = [
        "id"         => "int",
        "email"      => "string",
        "password"   => "password",
        "name"       => "string",
        "surname"    => "string",
        "secondname" =>"string",
        "type"       => ["simple","organizator","admin"],
        "birthday"   => "date",
        "city"       => "int",
        "adress"     => "string",
        "requsites"  => "string",
        "last_num"   => "string",
        "date_reg"   => "datetime"
    ];

    public function saveUser($form)
    {
        $form = $this->clearForm($form, $this->user);
        return $this->updateobject('users', $form);
    }
    
    public function getOrganizators()
    {
        return $this->db->select("select id,id as ARRAY_KEY, name, surname, secondname from users where type in ('simple','admin')");
    }
    
    public function updatePassUser($user, $pass)
    {
        if(!$user){
            throw new \Exception("Пользователя не существует!");
        }
        $this->updateObject('users',[
            'password' => md5($pass),
            'id'       => $user
        ]);
        
        $notice = new \app\controller\notice();
        $notice->updatePassUser($user, $pass);
        
        return [
            'stat' => 1
        ];
    }
}
