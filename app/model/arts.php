<?php

namespace app\model;


class arts extends base
{
    public function getArtsStruct($id)
    {
        return $this->db->select("select * from arts where struct=?d && parent=0",$id);
    }
    
    public function getTreeArts($id)
    {
        return $this->db->select("select *,id as ARRAY_KEY,`parent` as PARENT_KEY from arts where struct=?d order by `order`",$id);
    }
    
    public function getArt($id)
    {
        return $this->db->SelectRow("select * from arts where id=?d",$id);
    }
    
    public function deleteArt($id)
    {
        $this->deleteObject('arts', $id);
        $this->db->query("UPDATE arts set parent=0 where parent=?d",$id);
    }
    
    public function updateArtsTree($list)
    {
        return $this->updateTree('arts',$list);
    }
    
    public function updateStepsTree($list)
    {
        return $this->updateTree('steps',$list);
    }
    
    public function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
            ' ' => '_'
        );
        return strtr($string, $converter);
    }
    
    public function saveArt($form)
    {
        if($form['show_main'] && !$form['link']){
            $form['link'] = $this->rus2translit($form['name']);
            $form['link'] = preg_replace("/[^a-zа-я\s_]/iu","", $form['link']);
            $form['link'] = mb_substr($form['link'], 0, 50);
        }
        return $this->updateobject('arts', $form);
    }
    
    public function getMainLinks()
    {
        return $this->db->select("select id,name,link from arts where show_main=1");
    }
    
    public function getSteps($struct)
    {
        return $this->db->select("select * from steps where struct=?d order by `order`",$struct);
    }
    
    public function getStep($id)
    {
        return $this->db->selectRow("select * from steps where id=?d",$id);
    }
    
    public function saveStep($form)
    {
        return $this->updateObject('steps', $form);
    }
}
