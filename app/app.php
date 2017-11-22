<?php

namespace app;

class app{
    
    static $db     = null;
    static $args   = null;
    static $widget = null;
    static $my     = null;
    
    public function __construct($param, $access)
    {
        $this->param  = $param;
        self::$db     =  new model\dataBase($param['db']);
        self::$my     = $this;
        $this->access = $access;
    }
    
    public function run($args, $send)
    {
        $controller = "\app\controller\\".$args['controller'];
        $method     = $args['action'];
        
        if(class_exists($controller)){
            $controller = new $controller();
        }else{
            $this->error404($args);
        }
        
        if( ! \app\app::$my->checkAccess($args['controller'],$method) ){
            die("Доступ запрещён");
        }
        
        if(method_exists($controller, $method)){
            try{
                ob_start();
                $controller->$method($args, $send);
            }catch(\Exception $e){
                echo $e->getMessage();
            }
        }else{
            $this->error404($args);
        }
    }
    
    public function error404($args)
    {
        pr($args);
        echo file_get_contents($_SERVER['DOCUMENT_ROOT']."/view/404.html");
        die();
    }
    
    public function isAdmin()
    {
        if($_SESSION['user']['type'] == 'admin'){
            return true;
        }
    }
    
    public function isAuth()
    {
        if($_SESSION['user']){
            return true;
        }
    }
    
    public static function getWidget()
    {
        if(!self::$widget){
            self::$widget = new \app\controller\widget();
        }
        return self::$widget;
    }
    
    public function checkAccess($controller, $method)
    {
        if( !isset($this->access[$controller]) || !isset($this->access[$controller][$method])  ){
            return true;
        }else{
            if(in_array($_SESSION['user']['type'], $this->access[$controller][$method]) ){
                return true;
            }else{
                return false;
            }
        }
    }
}