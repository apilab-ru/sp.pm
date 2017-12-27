<?php

namespace app\controller;

class ajax extends base{
    
    public function ajax($args=null, $param)
    {
        $controller = $args['subcontroller'];
        $class      = "app\controller\\" . $controller;
        $action     = ($args['subaction']) ? $args['subaction'] : "main";
        
        $send = $param['send'];
        if(is_string($send)){
            $send = json_decode($send, 1);
        }
        
        if( ! \app\app::$my->checkAccess($controller,$action) ){
            die("Доступ запрещён");
        }
        
        if(!$send){
            $data = file_get_contents('php://input');
            if($data){
                $send = json_decode($data,1);
            }
        }
        
        try{
            ob_start();
            $class = new $class();
            if(!method_exists($class, $action)){
                throw new \Exception("Неправильный запрос $controller-> " . $action);
            }
            $res = $class->{$action}($args, $send);
            $html = ob_get_clean();
        }catch(\Exception $e){
            $html = ob_get_clean();
            $res = [
                'stat'  => 0,
                'error' => $e->getMessage()
            ];
        }
        
        if($send['ja']){
            echo $html;
            if(!$res && $html){
                $res = ['stat' => 1];
            }
            echo "<ja>" . json_encode($res, JSON_UNESCAPED_UNICODE) . "</ja>";
        }else{
            if(!$res && $html){
                $res = ['stat' => 1];
            }
            if($html){
                $res['html'] = $html;
            }

            if($res){
                header('Content-Type: application/json');
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    
}
