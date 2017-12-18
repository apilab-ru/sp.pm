<?php

namespace app\controller;
use \app\model\messages;
use \app\model\basket;
use \app\model\menu;

class page extends base
{
    
    public function main($content, $args=[])
    {
        $menu = new menu($args['struct']);
        
        if($_SESSION['user']){
            $messages = new messages();
            $message = [
                'count' => $messages->getCountUnread($_SESSION['user']['id']),
                'time'  => $messages->getTimeLastMessage($_SESSION['user']['id'])
            ];
        }
        
        return $this->render('page/index',[
            'user'        => $_SESSION['user'],
            'content'     => $content,
            'menu'        => $menu->getList(),
            'title'       => $menu->getTitle(),
            'description' => $menu->getDescription(),
            'basket'      => new basket(),
            'message'     => $message
        ]);
    }
    
    public function authPage()
    {
        echo $this->main($this->authBox());
    }
    
    public function authBox()
    {
        return "Вы не авторизованны";
    }
    
}
