<?php

namespace app\model;

class photobook extends base
{
    private $dir;
    public function __construct() {
        parent::__construct();
        $this->dir = $_SERVER['DOCUMENT_ROOT']."/book/";
    }
    
    public function getCats()
    {
        return $this->db->select("select * from cats order by sort");
    }
    
    public function getPublishTemplates()
    {
        $list = $this->db->select("select * from templates where publ=1 order by sort");
        foreach ($list as $key => $item) {
            $list[$key]['template'] = $this->decodeTemplate($item['template']);
            $i = 0;
            foreach($list[$key]['template'] as $num=>$page){
                foreach($page['photos'] as $num2=>$it){
                    $list[$key]['template'][$num]['photos'][$num2]['index'] = $i;
                    $i ++;
                }
            }
        }
        return $list;
    }
    
    public function addCat($arr = null)
    {
        if(!$arr['name']){
            $arr['name'] = "Новая категория";
        }
        $arr['id'] = $this->db->insert("cats",$arr);
        return $arr;
    }
    
    public function saveCats($list)
    {
        $sql = "UPDATE `cats` SET `name`= CASE id";
        foreach($list as $item){
            $sql .= " when {$item['id']} then '{$item['name']}' ";
        }
        $sql .= "end, `sort` = CASE id";
        $ids = [];
        foreach($list as $item){
            $sql .= " when {$item['id']} then '{$item['sort']}' ";
            $ids[] = $item['id'];
        }
        $sql .= " end where id in(" . implode(",",$ids).")";
        
        $stat = $this->db->query($sql);
    }
    
    public function removeCat($id)
    {
        $this->db->query("DELETE FROM `cats` WHERE id=?d",$id);
    }
    
    public function getTemplates($cat)
    {
        return $this->db->select("select * from templates where publ=1 order by sort");
    }
    
    public function getAllTempaltes()
    {
        $list = $this->db->select("select * from templates order by sort");
        foreach($list as $key=>$item){
            $list[$key]['template'] = $this->decodeTemplate($item['template']);
        }
        return $list;
    }
    
    public function decodeTemplate($tpl)
    {
        $pages = json_decode($tpl,1);
        foreach($pages as $kp=>$mas){
            foreach($mas['photos'] as $key=>$item){
                if(!$item['left']){
                    $pages[$kp]['photos'][$key]['left'] = $item['x'];
                }
                if(!$item['top']){
                    $pages[$kp]['photos'][$key]['top'] = $item['y'];
                }
            }
        }
        if(!$pages){
            $pages = array();
        }
        return $pages;
    }
    
    public function addTempalte($arr=null)
    {
        if(!$arr['name']){
            $arr['name'] = "Новый шаблон";
        }
        if($arr['template'] && !is_string($arr['template'])){
            $arr['template'] = json_encode($arr['template']);
        }
        $arr['id']  = $this->db->insert('templates',$arr);
        mkdir($this->dir . $arr['id']);
        return $arr;
    }
    
    public function getTempalte($id)
    {
        $row = $this->db->selectRow("select * from templates where id=?d",$id);
        $row['template'] = json_decode($row['template'],1);
        return $row;
    }
    
    public function copyTemplatePhoto($old, $new)
    {
        $list = glob($this->dir.$old."/*.png");
        foreach($list as $item){
            $items = explode("/", $item);
            $item = $items[ count($items)-1 ];
            $res = copy( $this->dir.$old."/".$item, $this->dir.$new."/".$item);
        }
    }
    
    public function saveTemplateBook($book)
    {
        $images = [];
        $dir = $_SERVER['DOCUMENT_ROOT']. "/book/" . $book['id'] . "/";
        foreach($book['template'] as $key=>$item){
            $images[] = $dir . $item['background'].".png";
            foreach($item['photo'] as $key2=>$it){
               unset($book['template'][$key]['photo'][$key2]['rect']); 
            }
        }
        $book['template'] = json_encode($book['template']);
        $this->db->update('templates',[
            'template' => $book['template']
        ], $book['id']);
        
        
        $files = glob($dir."*.png");
        foreach($files as $file){
            if(!in_array($file, $images) ){
                unlink($file);
            }
        }
    }
    
    public function delteTempalte($id)
    {
        $this->db->query("delete from templates where id=?d",$id);
        unlink($this->dir . $id); 
    }
    
    public function saveListTemplates($list)
    {
        $this->db->updateArr('templates',$list);
    }
    
    public function saveBook($id, $data, $name='')
    {
        if($id){
            if($this->db->selectCell("select id from books where client_id=?d && id=?d",
                $_SESSION['client']['id'],
                $id
            ))
            $this->db->update('books',[
                'data' => json_encode($data)
            ], $id);
        }else{
            $id = $this->db->insert('books',[
                'data'      => json_encode($data,JSON_UNESCAPED_UNICODE),
                'client_id' => $_SESSION['client']['id'],
                'name'      => $name
            ]);
        }
        return $id;
    }
    
    public function getBook($id)
    {
        $book = $this->db->selectRow("select * from books where id=?d",$id);
        $book['data'] = json_decode($book['data'],1);
        return $book;
    }
    
    public function updateCoverBook($bookId, $coverId)
    {
        $this->db->update('books',[
            'cover_id' => $coverId
        ], $bookId);
    }
    
    public function getBooksClient($id)
    {
        return $this->db->select("Select id,date_create,name,cover_id from books where client_id=?d",$id);
    }
}
