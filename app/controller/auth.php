<?php

namespace app\controller;

class auth extends base
{
    public function isOrganizator()
    {
        if($_SESSION['user']['type'] == 'admin' || $_SESSION['user']['type']=='organizator'){
            return true;
        }
    }
    
    public function login($args, $send)
    {
        $auth = new \app\model\auth();
        if($send['password']){
            $send['pass'] = $send['password'];
        }
        $user = $auth->checkAccount($send['email'], $send['pass']);
        if($user){
            $auth->userAuth($user);
            return [
                'stat' => 1,
                'user' => $user
            ];
        }else{
            return [
                'stat'  => 0,
                'error' => "Email или пароль не верны"
            ];
        }
    }
    
    public function out()
    {
        $auth = new \app\model\auth();
        unset($_SESSION['user']);
        //$auth->out();
        return [
            'stat'  => 1
        ];
    }
    
    public function reg($args, $send)
    {
        $auth = new \app\model\auth();
        $stat = $auth->validation( $send );
        
        if(!$stat['stat']){
            return $stat;
        }else{
            $stat = $auth->registration( $stat['form'] );
            if($stat['id']){
                $auth->userAuth( $auth->getUser( $stat['id'] ) );
            }else{
                $stat['error'] = "Ошибка регистрации";
            }
        }
        return $stat;
    }
}
