<?php


namespace app\controller;

class main extends base{
    
    public function main()
    {
       echo $this->render('main/mainBox', array(
                'cats' => (new \app\model\catalog())->getMainList()
            ));
    }
    
    public function faq()
    {
        echo $this->render('main/faq', [
            'arts' => (new \app\model\arts())->getTreeArts(2)
        ]);
        return [
            "struct" => "faq"
        ];
    }
    
    public function oplata()
    {
        echo  $this->render('main/oplata', []);
        return [
            "struct" => "pay"
        ];
    }
    
    public function delivery()
    {
        echo $this->render('main/delivery');
        return [
             "struct" => 'delivery'
        ];
    }
    
    public function editFaq()
    {
        //$widget  = new widget();
        //return $widget->tableAndFilter("","");
        $arts = new \app\model\arts();
        $struct = 2;
        return $this->render('main/editFaq',[
            'list'   => $arts->getTreeArts($struct),
            'struct' => $struct
        ]);
    }
    
    public function editArt($param)
    {
        $arts = new \app\model\arts();
        if($param['send']['id']){
            $object = $arts->getArt($param['send']['id']);
        }else{
            $object = [
                'struct' => $param['send']['param']
            ];
        }
        echo $this->render('main/formEditArt',[
            'object' => $object,
            'cats'   => $arts->getArtsStruct($object['struct'])
        ]);
    }
    
    public function saveArt($send)
    {
        $arts = new \app\model\arts();
        $id = $arts->updateobject('arts', $send['send']['form']);
        if(!$id){
            $stat = ["error" => "Ошибка сохранения"];
        }else{
            $stat = ["stat" => 1];
        }
        return $stat;
    }
    
    public function deleteArt($send)
    {
        $arts = new \app\model\arts();
        $arts->deleteArt($send['send']['id']);
        return [
            'stat' => 1
        ];
    }
    
    public function updateArtsTree($send)
    {
        $arts = new \app\model\arts();
        $arts->updateArtsTree($send['send']['list']);
    }

}
