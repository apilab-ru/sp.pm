<?php

namespace app\controller;

class admin extends base
{
    public function __construct()
    {
        if( !\app\app::$my->isAuth() ){
            $this->auth();
            die();
        }else{
            if(!\app\app::$my->isAdmin()){
                die("Доступ закрыт");
            }
        }
    }
    
    public $menu = [
        /*"" => [
          "name" => "Главная"  
        ],*/
        "users" => [
            "name" => "Сотрудники и пользователи",
            "list" => [
                "employees" => ["name" => "Список"],
            ]
        ],
        "catalog" => [
            "name" => "Закупки",
            "list" => [
                "purchaseTable" => ["name" => "Список закупок"],
                "stockTable"    => ["name" => "Товары закупок"],
                "catsEdit"      => ["name" => "Категории"]
            ]
        ]
    ];
    
    public function getMenu($args=null)
    {
        $sections = $this->menu;
        
        $this->cursor = array();
        
        foreach($sections as $section=>$data){
            if($section == $args['subcontroller']){
                $sections[$section]['check'] = 1;
                $this->cursor[] = array(
                    'name' => $data['name'],
                    'link' => "/admin/" . $section . "/"
                );
            }
            foreach($data['list'] as $key=>$item){
                if($section == $args['subcontroller'] && $key==$args['subaction']){
                    $sections[$section]['list'][$key]['check'] = 1;
                    
                    $this->cursor[] = array(
                        'name' => $item['name'],
                        'link' => "/admin/" . $section . "/" . $key . "/"
                    );

                    break;
                }
            }
        }
        
        if(!$this->cursor){
            $this->cursor[] = array(
                "link" => '/admin/',
                "name" => 'Главная'
            );
        }
        
        return $sections;
    }
    
    public function main($args)
    {
        echo "Добро пожаловать";
    }    
    
    public function auth()
    {
        echo $this->render('admin/auth');
    }

    public function ajax($args, $param)
    {
        if( $args['subcontroller'] == "admin" ){
            $class = $this;
        }else{
            $class = "app\controller\\" . $args['subcontroller'];
            $class = new $class();
        }
        
        if( ! \app\app::$my->checkAccess($args['subcontroller'],$args['subaction']) ){
            die("Доступ запрещён");
        }
        
        $res = [];
        
        if($param['send'] && is_string($param['send'])){
            $param = json_decode($param['send'],1);
        }
        
        ob_start();
        $re = $class->{$args['subaction']}($param);
        $res['html'] = ob_get_clean();
        
        if(gettype($re) == "array"){
           $res = $re;
        }else{
            $res['html'] .= $re; 
        }
        
        if($re['mode'] == 'html'){
            echo $res['html'];
            die();
        }
        
        if($param['send']['getmenu']){
            $this->getMenu($args);
            $res['navi'] = $this->render('admin/navi',[
                'cursor' => $this->cursor
            ]);
        }
        
        header('Content-Type: application/json');
        echo json_encode($res);
    }
    
    public function page($args, $param)
    {
        if( $args['subcontroller'] == "admin" ){
            $class = $this;
        }else{
            $class = "app\controller\\" . $args['subcontroller'];
            $class = new $class();
        }
        
        if( ! \app\app::$my->checkAccess($args['subcontroller'],$args['subaction']) ){
            die("Доступ запрещён");
        }
        
        ob_start();
        $res = $class->{$args['subaction']}($param);
        $html = ob_get_clean();
        $html .= $res;
        
        echo $this->render('admin/main',array(
            'user'   => (new \app\model\auth())->getUser(),
            'menu'   => $this->getMenu($args),
            'cursor' => $this->cursor,
            'html'   => $html
        ));
    }
    
}
