<?php


namespace app\controller;

class main extends base{
    
    public function main()
    {
       echo $this->render('main/mainBox', array(
                'cats' => (new \app\model\catalog())->getMainList(),
                'links' => (new \app\model\arts())->getMainLinks()
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
        $arts     = new \app\model\arts();
        $art = $arts->getArtsStruct(3);
        $art = $art[0];
        $steps = $arts->getSteps(3);
        echo  $this->render('main/oplata', [
            'art'   => $art,
            'steps' => $steps
        ]);
        return [
            "struct" => "pay"
        ];
    }
    
    public function delivery()
    {
        $arts     = new \app\model\arts();
        $delivery = new \app\model\delivery();
        
        $delivers = $delivery->getListDelivery();
        
        $art = $arts->getArtsStruct(5);
        $art = $art[0];
        $art['params'] = $delivery->parsePoint($art['params']);
        
        $steps = $arts->getSteps(5);
        
        echo $this->render('main/delivery',[
            'art'      => $art,
            'delivers' => $delivers,
            'steps'    => $steps
        ]);
        return [
             "struct" => 'delivery'
        ];
    }
    
    public function editFaq()
    {
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
        $id = $arts->saveArt($send['send']['form']);
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
    
    public function listDelivery()
    {
        $widget   = new widget();
        $delivery = new \app\model\delivery();

        $data = $delivery->getDataList();
        
        return $widget->tableAndFilter(
            $widget->tableByFilter(
                $data, [
                    "title"  => "Список доставки",
                    "add"    => "/admin/main/editDelivery",
                    "edit"   => "/admin/main/editDelivery",
                    "delete" => "/admin/main/deleteDelivery",
                    "labels" => [
                        "id"      => "id",
                        "address" => "Адресс",
                        "descr"   => "Описание"
                    ]
                ]), ""
        );
    }
    
    public function editDelivery($args)
    {
        if($args['send']['id']){
           $delivery = new \app\model\delivery();
           $object   = $delivery->getDelevery($args['send']['id']);
        }
        echo $this->render('main/editDelivery',[
            'object' => $object
        ]);
    }
    
    public function deleteDelivery($send)
    {
        $delivery = new \app\model\delivery();
        $delivery->deleteObject('delivers', $send['send']['id']);
        return [
            'stat'=>1
        ];
    }
    
    public function saveDelivery($send)
    {
        $delivery = new \app\model\delivery();
        $delivery->saveDelivery($send['send']['form']);
        return [
            'stat' => 1
        ];
    }
    
    public function editPageDelivery()
    {
        $arts = new \app\model\arts();
        $steps = $arts->getSteps(5);
        $art   = $arts->getArtsStruct(5);
        
        $art = $art[0];
        
        echo $this->render('main/editStepPage',[
            'art'   => $art,
            'steps' => $steps,
            'title' => 'Страница доставки',
            'struct' => 5
        ]);
    }
    
    public function editPagePay()
    {
        $st  = 3;
        $arts = new \app\model\arts();
        $steps = $arts->getSteps($st);
        $art   = $arts->getArtsStruct($st);
        
        $art = $art[0];
        
        echo $this->render('main/editStepPage',[
            'art'   => $art,
            'steps' => $steps,
            'title' => 'Страница оплаты',
            'struct' => $st
        ]);
    }
    
    public function editStep($param)
    {
        if($param['send']['id']){
            $arts = new \app\model\arts();
            $step = $arts->getStep($param['send']['id']);
        }else{
            $step = $param['send']['param'];
        }
        echo $this->render('main/formEditStep',[
            'object' => $step
        ]);
    }
    
    public function saveTextPageDelivery($param, $send)
    {
        $arts = new \app\model\arts();
        $art   = $arts->getArtsStruct(5);
        $art = $art[0];
        $arts->updateObject('arts', [
            'text'   => $send['text'],
            'id'     => $art['id'],
            'struct' => 5
        ]);
    }
    
    public function saveTextPage($param, $send)
    {
        $struct = intval( $send['struct'] );
        
        $arts = new \app\model\arts();
        $art   = $arts->getArtsStruct($struct);
        $art = $art[0];
        $arts->updateObject('arts', [
            'text'   => $send['text'],
            'id'     => $art['id'],
            'struct' => $struct
        ]);
        
        return [
            "stat" => 1
        ];
    }
    
    public function deleteStep($param, $send)
    {
        $arts = new \app\model\arts();
        $arts->deleteObject('steps', $send['id']);
    }
    
    public function saveStep($param)
    {
        $arts = new \app\model\arts();
        $arts->saveStep($param['send']);
        return [
            'stat' => 1
        ];
    }
    
    public function updateOrderSteps($params,$send)
    {
        $arts = new \app\model\arts();
        $arts->updateStepsTree($send['list']);
        return ['stat' => 1];
    }

}
