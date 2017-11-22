<?php


namespace app\controller;

class main extends base{
    
    public function main()
    {
        echo (new page())->main(
            $this->render('main/mainBox', array(
                'cats' => (new \app\model\catalog())->getMainList()
            ))
        ); 
    }
    
    public function faq()
    {
        echo (new page())->main(
            $this->render('main/faq', [
                'arts' => (new \app\model\arts())->getArtsStruct(2)
            ]),[
                "struct" => "faq"
            ]
        ); 
    }
    
    public function oplata()
    {
        echo (new page())->main(
            $this->render('main/oplata', [
                //'art' => (new \app\model\arts())->getArtsStruct(2)
            ]),[
                "struct" => "pay"
            ]
        ); 
    }
    
    public function delivery()
    {
        echo (new page())->main(
            $this->render('main/delivery'),
            [
                "struct" => 'delivery'
            ]
        );
    }

}
