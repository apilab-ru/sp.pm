<?php

namespace app\controller;

class images extends base
{
    //public $dir = '/photos/';
    //public $cache = '/cachephoto/';
    public function add($args, $param)
    {
        if($param['parent'] == 'user' 
                || (new \app\controller\auth())->isOrganizator()){
            
            return $this->savePhotoUser($_FILES['file'], $_SESSION['user']['id']);
        }
    }
    
    public function savePhotoUser($file, $user)
    {
        $model = new \app\model\images();
        $res = $model->uploadFile($file, 'user');
        $id  = $model->addImage($res['name'], $res['title'], $res['folder'], 'user', $user, $res['type']);

        $res['id'] = $id;

        $old = $model->getOldPhotoUser($user);

        if($old['id'] != $id){
            $model->removePhotoFile($old['folder'], $old['name'], $old['type']);
            $model->removePhoto($old['id']);
        }
        return $res;
    }
    
    /*private function uploadFile($file, $parent)
    {
        
        $model->sa
    }*/
    
    private function getTypeFile($fileType)
    {
        switch($fielType){
            case 'image/png':
                return 'png';
                break;
            
            case 'image/jpg':
                return 'jpg';
                break;
            
            case 'image/gif':
                return 'gif';
                break;
            
            case 'image/bmp':
                return 'bmp';
                break;
            
            default:
                throw new Exception('Неподдерживаемый тип файла');
                break;
        }
    }
    
    public function genCache($args)
    {
        $file = $args['file'];
        $model = new \app\model\images();
        $model->createCache($args['parent']."/".$args['year']."/".$args['month'], $args['file'], $args['tpl'], $args['ext']  );
    }
    
    public function upload()
    {
        $model = new \app\model\images();
        $res = $model->uploadFile($_FILES['upload'], 'other');
        $id  = $model->addImage($res['name'], $res['title'], $res['folder'], 'other', $user, $res['type']);
        
        $url = "/cachephoto" . $res['folder'] . $res['name'] . "_0x0x2." . $res['type'];
        
        $html = "<script>
                window.parent.CKEDITOR.tools.callFunction({$_GET['CKEditorFuncNum']}, '$url');
            </script>";
                
        return[
            'mode' => 'html',
            'html' => $html
        ];
    }
    /*public function createCacheBook($args) 
    {
        $cacheImg = $args[2];
        $imgName = explode("_", $cacheImg);
        $tpl = explode(".", $imgName[1]);
        $type = $tpl[1];
        $tpl = $tpl[0];
        $origImg = $imgName[0] . "." . $type;
        //pr($imgName, $tpl, $origImg, $type);
        $path      = $_SERVER['DOCUMENT_ROOT'] . "/book/{$args[1]}/{$origImg}";
        $cachePath = $_SERVER['DOCUMENT_ROOT'] . "/book/{$args[1]}/{$cacheImg}";

        
        $model = new \app\model\images();
        
        $listSet = (new \app\model\setting())->getSetting();
        
        if($tpl == 'pc'){
            $tpl = $listSet['imgTplPc'];
        }else{
            $tpl = $listSet['imgTplMobile'];
        }
        $tpl['change'] = 2;
        
        $file     = $model->loadImage($path, $type);
        $sizeOrig = $model->getSize($path);
        $sizeTpl  = $model->getTplSize($tpl, $sizeOrig);
        
        $cache = $model->imgResize($file, $type, $tpl, $sizeTpl, $sizeOrig);

        $model->saveImageAbsolute($cachePath, $type, $cache);
        $model->echoImg($cache, mime_content_type($cachePath), $type);
    }
    
    public function clearCacheBook($type)
    {
        $type = strtolower($type);
        $books = glob($_SERVER['DOCUMENT_ROOT']."/book/*");
        foreach($books as $book){
            $list = glob($book."/*_".$type.".*");
            if($list){
                foreach($list as $item){
                    unlink($item);
                }
            }
        }
    }*/

}

