<?php

namespace app\model;

class auth extends base
{
    public function checkAccount($email, $pass)
    {
        $user = $this->db->selectRow("select * from users where email=? && password=?",$email, md5($pass));
        return $user;
    }
    
    public function userAuth($user)
    {
        $_SESSION['user'] = $user;
    }
    
    public function getUser($id=null)
    {
        if(!$id){
            $id = $_SESSION['user']['id'];
        }
        return $this->db->SelectRow("select id,surname,name,secondname,email from users where id=?d",$id);
    }
    
    public function out()
    {
        unset($_SESSION['user']);
    }
    
    private $user = array(
        'email'    => "Заполните Email",
        'name'     => "Заполните Имя",
        'password' => "Заполните пароль"
    );
    
    public function validation($form)
    {
        foreach($form as $key=>$item){
            if( !isset($this->user[$key]) ){
                unset( $form[$key] );
            }
        }
        
        foreach($this->user as $key=>$item){
            if($item && !$form[$key]){
                return [
                    'stat'  => 0,
                    'error' => $this->user[$key]
                ];
            }
        }
        
        return [
            'stat' => 1,
            'form' => $form
        ];
    }
    
    public function registration($send)
    {
        $id = $this->db->insert('users',$send);
        return [
            'id'   => $id,
            'stat' => ($id) ? 1 : 0
        ];
    }
    
    /*
    public function restartSession()
    {
        $_SESSION['client'] = $this->getClientById($_SESSION['client']['id']);
    }
    
    public function getClinetByCookie($cookie)
    {
        return $this->db->selectRow("select * from clients where cookies=?",$cookie);
    }
    
    public function genCookie()
    {
        return md5('instabook' . time());
    }
    
    public function getClientById($id)
    {
        return $this->db->selectRow("select * from clients where id=?d",$id);
    }
    
    public function findClinetVk($id)
    {
        return $this->db->selectRow("select * from clients where vk_id=?d",$id);
    }
    
    public function addClient($cookie)
    {
        $id = $this->db->insert('clients',[
            'cookies' => $cookie
        ]);
        return $id;
    }
    
    public function autoAuthUser()
    {
        if($_SESSION['client']){
            return $_SESSION['client'];
        }
        $cookie = $_COOKIE['instaauth'];
        if($cookie){
            $user = $this->getClinetByCookie($cookie);
        }
        
        if(!$cookie || !$user){
            $cookie = $this->genCookie();
            $id     = $this->addClient($cookie);
            $user   = $this->getClientById($id);
        }
        $_SESSION['client'] = $user;
        setcookie('instaauth', $cookie, time()+60*60*24*30);
        return $user;
    }
    
    public function getClient($id)
    {
        return $this->db->selectRow("select * from clients where id=?d",$id);
    }
    
    public function updateClient($id, $param)
    {
        return $this->db->update('clients', $param, $id);
    }
    
    public function getMainTokenInstagram()
    {
        return $this->db->selectRow('select * from auth order by id ASC limit 1');
    }
    
    public function getCurrentToken()
    {
        if($_SESSION['client']['instagram_token']){
            return [
                'token' => $_SESSION['client']['instagram_token'],
                'uid'   => $_SESSION['client']['instagram_id']
            ];
        }
        return $this->getMainTokenInstagram();
    }
    
    public function updateMainToken($arr)
    {
        $this->db->update('auth', $arr, 1);
    }*/
    
}
