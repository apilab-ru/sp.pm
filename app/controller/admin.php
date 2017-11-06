<?php

namespace app\controller;

class admin extends base
{
    public $menu = [
        "#/cats/" => [
            "name"  => "Категории",
            "check" => 1
        ],
        "#/photobooks/" => [
            "name" => "Фото шаблоны"
        ],
        '#/panel/' => [
            "name" => "Панель настроеек и авторизации"
        ]
    ];
    
    public function main()
    {
        if( \app\app::$my->isAuth() ){
            if(!\app\app::$my->isAdmin()){
                die("Доступ закрыт");
            }else{
                echo $this->render('admin/main',array(
                    "data" => array(
                       'user' => (new \app\model\auth())->getUser() 
                    )
                ));
            }
        }else{
            $this->auth();
        }
    }

    public function auth()
    {
        echo $this->render('admin/auth');
    }

    public function run($param)
    {
        $args = \app\app::$args;
        //pr($args);
        if($args[1] == 'ajax' && !$_SESSION['user']['type']=='admin'){
            die('error auth');
        }
        if($_SESSION['user']['type'] == 'admin'){
            
            if($args[1] == 'ajax'){
                $this->ajax($args, $param);
            }else{
                if(!$args[1]){
                    $args[1] = 'main';
                }
                $this->{'action'.$args[1]}($args, $params);
            }
            
        }else{
            header('location: /auth/?from=' . $_SERVER['REQUEST_URI']);
        }
    }
    
    public function actionMain()
    {
        echo $this->render('admin/admin',[
            'menu' => $this->menu,
            'user' => $_SESSION['user']
        ]);
    }
    
    public function ajax($args, $param)
    {
        $data = file_get_contents('php://input');
        if($data){
            $param = json_decode($data,1);
        }
        $res = $this->{'ajax'.$args[2]}($param);
        echo json_encode($res);
    }
    
    public function ajaxAddCat()
    {
        return (new \app\model\photobook())->addCat();
    }
    
    public function ajaxSaveCats($send)
    {
        (new \app\model\photobook())->saveCats($send);
    }
    
    public function ajaxRemoveCat($send)
    {
        (new \app\model\photobook())->removeCat($send['id']);
        return ['stat'=>1];
    }
    
    public function ajaxGetTempaltes($send)
    {
        return (new \app\model\photobook())->getAllTempaltes();
    }
    
    public function ajaxAddTemplate()
    {
        return (new \app\model\photobook())->addTempalte();
    }
    
    public function ajaxSaveListTemplate($param)
    {
        return (new \app\model\photobook())->saveListTemplates($param);
    }
    
    public function ajaxDeleteTemplate($send)
    {
        return (new \app\model\photobook())->delteTempalte($send['id']);
    }
    
    public function ajaxUploadBack($send)
    {
        $file = microtime(true) . ".png";
        move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/tmpfiles/" . $file);
        return [
            'file' => $file
        ];
    }
    
    public function ajaxSaveFiles($send)
    {
        $tmp =  $_SERVER['DOCUMENT_ROOT']."/tmpfiles/";
        $dir =  $_SERVER['DOCUMENT_ROOT']."/book/" . $send['book'] . "/";
        
        foreach($send['list'] as $key=>$item){
            $send['list'][$key]['stat'] = rename($tmp.$item['file'],$dir.$item['file']);
            $send['list'][$key]['file'] = str_replace(".png", "", $item['file']);
        }
        return $send['list'];
    }
    
    public function ajaxSaveBook($send)
    {
        return (new \app\model\photobook())->saveTemplateBook($send['book']);
    }
    
    public function ajaxPanelData()
    {
        $auth = new \app\model\auth();
        return [
            'instagram'     => $auth->getMainTokenInstagram(),
            'instagramAuth' => (new \app\model\instagram())->authLink('/admin/ajax/instagramCallback'),
            'setting'       => (new \app\model\setting())->getSetting()
        ];
    }
    
    public function ajaxUpdateImgTpl($data)
    {
        (new \app\model\setting())->updateSetting('imgTpl'.$data['type'], $data['data']);
        (new \app\controller\images())->clearCacheBook($data['type']);
    }
    
    
    public function ajaxInstagramCallback()
    {
        $auth      = new \app\model\auth();
        $instagram = new \app\model\Instagram();
        $account   = $instagram->auth($_REQUEST['code'], '/admin/ajax/instagramCallback');

        if ($account) {
            $auth->updateMainToken([
                'uid'   => $account['uid'],
                'photo' => $account['photo'],
                'nick'  => $account['name'],
                'token' => $account['access_token']
            ]);
            echo "<script> opener.parent.admin.updateInstagram(". json_encode( $auth->getMainTokenInstagram() ) ."); window.close(); </script>";
        }else{
            echo " Ошибка получения токена ";
        }
    }
    
    public function ajaxCopeTempalte($send)
    {
        $model = new \app\model\photobook();
        $book = $model->getTempalte($send['id']);
        unset($book['id']);
        $book = $model->addTempalte($book);
        $model->copyTemplatePhoto($send['id'],$book['id']);
        
        return $book;
    }
}
