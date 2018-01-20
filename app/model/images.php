<?php

namespace app\model;

class images extends base{
    
    public function __construct() 
    {
        parent::__construct();
        $this->src = $_SERVER['DOCUMENT_ROOT'] . $this->dirOrig;
    }
    
    public $dirOrig = '/photos/';
    public $dirCreated = '/cachephoto/';
    public $defaultChange = 2;
    public $changeList = array(
        1 => "Область из центра",
        2 => "По максимальному отношению",
        3 => "Сохранение пропорций со смещённым центром"
    );
    
    public function createCache($folder, $name, $tpl, $ext)
    {
        $ext = strtolower($ext);
        
        $orig = $this->src . "{$folder}/{$name}.{$ext}";
        
        $file     = $this->loadImage($orig, $ext);
        
        $config   = $this->parseParams($tpl);
        $sizeOrig = $this->getSize($orig);
        $sizeTpl  = $this->getTplSize($config, $sizeOrig);
        $cache    = $this->imgResize($file, $ext, $config, $sizeTpl, $sizeOrig);
        
        $link     =  $_SERVER['DOCUMENT_ROOT'] . $this->dirCreated . "{$folder}/{$name}_{$tpl}.{$ext}";
        
        $dir = $_SERVER['DOCUMENT_ROOT'] ."/" . $this->dirCreated . $folder;
        
        if(!is_dir($dir)){
            if (!mkdir($dir, 0777, true)) {
                throw new \Exception('Ошибка создания кеша');
            }
        }
        
        $this->saveImage($link, $ext, $cache);
        $this->echoImg($cache, mime_content_type($link), $ext);
    }
    
    public function getTplSize($tpl, $sizeOrig) 
    {
        $sizeTpl = new \stdClass();

        $sizeTpl->width  = $tpl['width'];
        $sizeTpl->height = $tpl['height'];


        if ($sizeTpl->width == 0 && $sizeTpl->height == 0) {
            $sizeTpl->width = $sizeOrig->width;
            $sizeTpl->height = $sizeOrig->height;
        } else {

            if ($sizeTpl->width == 0) {
                $sizeTpl->width = round(($sizeOrig->width / $sizeOrig->height) * $sizeTpl->height);
            }

            if ($sizeTpl->height == 0) {
                $sizeTpl->height = round(($sizeOrig->height / $sizeOrig->width) * $sizeTpl->width);
            }
        }

        return $sizeTpl;
    }

    public function getSize($path) 
    {
        $ar = getimagesize($path);
        $size = new \stdClass();

        $size->width = $ar[0];
        $size->height = $ar[1];
        $size->delta = $size->width / $size->height;

        return $size;
    }

    private function imgResize($file, $typeFile, $tpl, $sizeTpl, $sizeOrig) 
    {
        switch ($tpl['change']) {
            case 1:
                //Область из центра
                if ($sizeOrig->delta < 1) {
                    $tempWidth = $sizeTpl->width;
                    $tempHeight = round(($sizeOrig->height / $sizeOrig->width ) * $sizeTpl->width);

                    $y = ceil(($tempHeight - $sizeTpl->height) / 2);
                    $x = 0;
                } else {
                    $tempHeight = $sizeOrig->height;
                    $tempWidth = round(($sizeOrig->width / $sizeOrig->height ) * $sizeTpl->height);

                    $y = 0;
                    $x = ceil(($tempWidth - $sizeTpl->width) / 2);
                }
                $tempImg = imagecreatetruecolor($tempWidth, $tempHeight);
                imagecopyresampled($tempImg, $file, 0, 0, 0, 0, $tempWidth, $tempHeight, $sizeOrig->width, $sizeOrig->height);

                $cache = imagecreatetruecolor($sizeTpl->width, $sizeTpl->height);
                if ($typeFile == 'png') {
                    imagealphablending($cache, false);
                    imagesavealpha($cache, true);
                    $transColour = imagecolorallocatealpha($cache, 0, 0, 0, 127);
                    imagefill($cache, 0, 0, $transColour);
                }
                imagecopyresampled($cache, $tempImg, 0, 0, $x, $y, $sizeTpl->width, $sizeTpl->height, $sizeTpl->width, $sizeTpl->height);
                return $cache;
                break;

            case 2:
                //По максимальному отношению
                if ($sizeOrig->width == $sizeOrig->height) {
                    $sizeTpl->width = $sizeTpl->height = min($sizeTpl->width, $sizeTpl->height);
                } elseif ($sizeTpl->width > $sizeTpl->height) {

                    if ($sizeOrig->delta > 1) {
                        $delta = $sizeOrig->height / $sizeOrig->width;
                        $sizeTpl->height = $sizeTpl->width * $delta;
                    } else {
                        $delta = $sizeOrig->delta;
                        $sizeTpl->width = $sizeTpl->heigh * $delta;
                    }
                } else {

                    if ($sizeOrig->delta > 1) {
                        $delta = $sizeOrig->delta;
                        $sizeTpl->width = $sizeTpl->height * $delta;
                    } else {
                        $delta = $sizeOrig->height / $sizeOrig->width;
                        $sizeTpl->height = $sizeTpl->width * $delta;
                    }
                }

                if ($sizeTpl->width == 0) {
                    $sizeTpl->width = ($sizeOrig->width / $sizeOrig->height) * $sizeTpl->height;
                }
                if ($sizeTpl->height == 0) {
                    $sizeTpl->height = ($sizeOrig->height / $sizeOrig->width) * $sizeTpl->width;
                }

                $cache = imagecreatetruecolor($sizeTpl->width, $sizeTpl->height);

                if ($typeFile == 'png') {
                    imagealphablending($cache, false);
                    imagesavealpha($cache, true);
                    $transColour = imagecolorallocatealpha($cache, 0, 0, 0, 127);
                    imagefill($cache, 0, 0, $transColour);
                }
                imagecopyresampled($cache, $file, 0, 0, 0, 0, $sizeTpl->width, $sizeTpl->height, $sizeOrig->width, $sizeOrig->height);
                break;

            case 3: // Сохранение пропорций со смещённым центром
                $cache = imagecreatetruecolor($sizeTpl->width, $sizeTpl->height);
                if ($typeFile == 'png') {
                    imagealphablending($cache, false);
                    imagesavealpha($cache, true);
                    $transColour = imagecolorallocatealpha($cache, 0, 0, 0, 127);
                    imagefill($cache, 0, 0, $transColour);
                }

                $h_ot_d = $sizeTpl->height / $sizeOrig->height; // Определяем отношение нужной высоты к исходной
                $w_ot_d = $sizeTpl->width / $sizeOrig->width; // И ширины

                if ($h_ot_d > $w_ot_d) { // Если отношение по высоте больше чем по ширине, то отталкиваемся от ширины
                    $fxc = round(($sizeOrig->width - $sizeTpl->width / $h_ot_d) / 2);
                    imagecopyresampled($cache, $file, 0, 0, $fxc, 0, $sizeTpl->width, $sizeTpl->height, round($sizeTpl->width / $h_ot_d), $sizeOrig->height);
                } elseif ($h_ot_d < $w_ot_d) {
                    $fyc = round(($sizeOrig->height - $sizeTpl->height / $w_ot_d) / 2);
                    imagecopyresampled($cache, $file, 0, 0, 0, $fyc, $sizeTpl->width, $sizeTpl->height, $sizeOrig->width, round($sizeTpl->height / $w_ot_d));
                } else {
                    imagecopyresampled($cache, $file, 0, 0, 0, 0, $sizeTpl->width, $sizeTpl->height, $sizeOrig->width, $sizeOrig->height);
                }
                break;

            default:
                $cache = imagecreatetruecolor($sizeTpl->width, $sizeTpl->height);
                if ($typeFile == 'png') {
                    imagealphablending($cache, false);
                    imagesavealpha($cache, true);
                    $transColour = imagecolorallocatealpha($cache, 0, 0, 0, 127);
                    imagefill($cache, 0, 0, $transColour);
                }
                imagecopyresampled($cache, $file, 0, 0, 0, 0, $sizeTpl->width, $sizeTpl->height, $sizeOrig->width, $sizeOrig->height);
                break;
        }

        return $cache;
    }

    private function parseParams($tpl) 
    {
        if (preg_match("/([0-9]*)x([0-9]*)x([0-9]*)/", $tpl, $match)) {
            $tpl = array(
                "width"  => $match[1],
                "height" => $match[2],
                "change" => $match[3]
            );
        } elseif (preg_match("/([0-9]*)x([0-9]*)/", $tpl, $match)) {
            $tpl = array(
                "width"  => $match[1],
                "height" => $match[2],
                "change" => $this->defaultChange
            );
        } else {
            $tpl = array(
                'width'  => 400,
                'height' => 400,
                'change' => 3
            );
        }

        return $tpl;
    }

    private function loadImage($path, $type) 
    {
        switch ($type) {
            case 'gif':
                $file = imagecreatefromgif($path);
                break;

            case 'jpeg':
            case 'jpg':
                $file = imagecreatefromjpeg($path);
                break;

            case 'png':
                $file = imageCreateFromPng($path);
                imageAlphaBlending($file, false);
                imageSaveAlpha($file, true);
                break;

            case 'bmp':
                $file = imagecreatefromwbmp($path);
                break;
            
            default:
                throw new \Exception('Неподдерживаемый тип файла ' . $type);
                break;
        }
        if(!$file){
            throw new \Exception('Файл не найден ');
        }
        return $file;
    }

    private function saveImage($path, $type, $file) 
    {
        switch ($type) {
            case 'png':
                imagepng($file, $path, 6);
                break;
            
            case 'gif':
                imagegif($file, $path);
                break;
            
            case 'bmp':
                image2wbmp($file, $path);
                break;
            
            default:
                imagejpeg($file, $path, 100);
                break;
        }
    }

    private function echoImg($file, $mime, $type) 
    {
        header('Content-Type: ' . $mime);
        switch ($type) {
            case 'png':
                imagepng($file, null, 0);
                break;
            
            case 'bmp':
                image2wbmp($file);
                break;
            
            case 'gif':
                imagegif($file);
                break;
            
            default:
                imagejpeg($file, null, 100);
                break;
        }
    }
    
    public function getTypeImage($image)
    {
        $args = explode(".", $image);
        $type = $args[ count($args)-1 ];
        $type = mb_strtolower($type);
        if($type == 'jpeg'){
            $type = 'jpg';
        }
        if(!in_array($type, ['jpg','gif','png','bmp'])){
            throw new \Exception("Неподдерживаемый тип файлов");
        }
        return $type;
    }
    
    public function uploadFile($file, $parent)
    {
        $type  = $this->getTypeImage($file['name']);
        $image = $this->loadImage($file['tmp_name'], $type);
        $exif  = exif_read_data($file['tmp_name'], 0, true);
        
        $folder = "$parent/". date("Y/m");
        
        if (false === empty($exif['IFD0']['Orientation'])) {
            switch ($exif['IFD0']['Orientation']) {
                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;
            }
        }
        
        $name  = explode(".",$file['name']);
        $title = $name[0];
        
        $name  =  date("d-H-i-") . str_replace(["_"," ","+"], "-", $title);
        
        $dir = $_SERVER['DOCUMENT_ROOT'] ."/" . $this->dirOrig . $folder;
        
        if(!is_dir($dir)){
            if (!mkdir($dir, 0777, true)) {
                throw new \Exception('Ошибка создания кеша');
            }
        }
        
        $this->saveImage($_SERVER['DOCUMENT_ROOT'] . $this->dirOrig . "$folder/" . $name . ".{$type}", $type, $image);
        
        return [
            'name'   => $name,
            'title'  => $title,
            'type'   => $type,
            'folder' => "/" . $folder . "/"
        ];
    }
    
    public function addImage($name, $title, $folder, $parent, $parentId, $type)
    {
        $data = [
            'name'      => $name,
            'title'     => $title,
            'folder'    => $folder,
            'parent'    => $parent,
            'parent_id' => intval($parentId),
            'type'      => $type
        ];
        
        $id = $this->db->query("INSERT INTO images (?#) VALUES (?a)", array_keys($data), array_values($data));
        
        if(!$id){
            throw new \Exception("Ошибка добавления файла " . print_r($this->db->getError(), true));
        }
        
        return $id;
    }
    
    public function getOldPhotoUser($id)
    {
        return $this->db->selectRow("select * from images where parent='user' && parent_id=?d ORDER by id ASC", $id);
    }
    
    public function remove($file)
    {
        $this->removePhoto($file['id']);
        $this->removePhotoFile($file['folder'], $file['name'], $file['type']);
    }
    
    public function removePhoto($id)
    {
        $this->db->query("DELETE FROM images where id=?d", $id);
    }
    
    public function removePhotoFile($folder, $name, $type)
    {
        $orig = $_SERVER['DOCUMENT_ROOT'] . $this->dirOrig . $folder . $name . ".$type";
        unlink($orig);
        $dir = $_SERVER['DOCUMENT_ROOT'] . $this->dirCreated . $folder . $name;
        $list = glob($dir . "_*");
        if($list){
            foreach($list as $item){
                unlink($item);
            }
        }
               
    }
    
    public function getFiles($data)
    {
        $files = array();
        foreach($data['name'] as $n=>$name){
            $files[] = array(
                'name'     => $name,
                'type'     => $data['type'][$n],
                'tmp_name' => $data['tmp_name'][$n],
                'size'     => $data['size'][$n]
            );
        }
        return $files;
    }
    
    public function getFilesByIds($ids)
    {
        return $this->db->select("select * from images where id in (?a)", $ids);
    }
    
    /*public function addImage($file)
    {
        $name = $file['name'];
        $type = $this->getTypeImage($name);
        
        $id = $this->db->insert("photos",array(
            'type'      => $type,
            'client_id' => $_SESSION['client']['id']
        ));
        
        $name = $id . "." . $type;
        
        $image = $this->loadImage($file['tmp_name'], $type);
        
        
        $this->db->update('photos',array(
            'path' => '/photos/' . $name
        ), $id);
        
        return array(
            'id'   => $id,
            'link' => '/photos/' . $name,
            'tmp'  => $this->getThmLink($id, $type)
        );
    }*/
    
    
    /*public function updateImage($file, $id)
    {
        $name = $file['name'];
        $type = $this->getTypeImage($name);
        $name = $id . "." . $type;
        $this->db->update('photos', array(
            'path' => '/photos/' . $name,
            'type' => $type
        ), $id);
        move_uploaded_file($file['tmp_name'], $this->src . $name);
        return array(
            'id'   => $id,
            'link' => '/photos/' . $name
        );
    }*/
    

}
