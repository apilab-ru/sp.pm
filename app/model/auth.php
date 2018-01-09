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
        header("Location: /");
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
    
}
