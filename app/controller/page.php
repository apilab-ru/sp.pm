<?php

namespace app\controller;

class page extends base
{
    
    public function main($content, $args=[])
    {
        $menu = new \app\model\menu($args['struct']);
        
        return $this->render('page/index',[
            'user'        => $_SESSION['user'],
            'content'     => $content,
            'menu'        => $menu->getList(),
            'title'       => $menu->getTitle(),
            'description' => $menu->getDescription(),
            'basket'      => new \app\model\basket()
        ]);
    }
    
    public function authPage()
    {
        echo $this->main(" Вы не авторизованны ");
    }
    
}
