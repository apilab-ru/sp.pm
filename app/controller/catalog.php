<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controller;

/**
 * Description of catalog
 *
 * @author Dekim
 */
class catalog extends base
{
    public function catalog($args, $param)
    {
        $catalog = new \app\model\catalog();
        
        echo (new page())->main(
            $this->render("catalog/catalog",[
                'cats'     => $catalog->getCats(),
                'discount' => $catalog->getDiscounts(),
                'tags'     => $catalog->getTags()
            ]),
            [
                'struct' => 'catalog'
            ]);
    }
    
    public function dataList($args, $param)
    {
        $catalog = new \app\model\catalog();
        $data = $catalog->getDataList($param);
        $data['discounts'] = $catalog->getDiscounts();
        $data['tags']      = $catalog->getTags();
        $data['func']      = 'catalog.reload';
        echo $this->render('catalog/dataList', $data);
        return [
            'stat' => 1,
            'pagination' => \app\app::getWidget()->pagination($data)
        ];
    }
    
    public function zakupka($args, $param)
    {
        $catalog = new \app\model\catalog();
        
        $purchase    = $catalog->getPurchase($args['id']);
        $organizator = (new \app\model\users())->getUser( $purchase['user'] );
        $user        = $_SESSION['user'];
        
        
        echo (new page())->main(
          $this->render('catalog/zakupka',[
              'purchase'    => $purchase,
              'organizator' => $organizator,
              'discounts'   => $catalog->getPurchaseDiscount($purchase['id']),
              'options'     => $catalog->getPurchaseOptions($purchase['id']), 
              'user'        => $user,
              'cats'        => $catalog->getPurchaseStockCats($purchase['id']),
              'isFavorite'  => $catalog->checkPurchaseFavorite($purchase['id'], $user['id'])
          ]),    
          [
            "struct" => "zakupha"
        ]);
    }
    
    public function setPurchaseFavorite($args, $param)
    {
        if(!$_SESSION['user']){
            throw new Exception("Вы не авторизованны");
        }
        if(!$param['purchase']){
            throw new Exception("Невыбрана закупка");
        }
        $catalog = new \app\model\catalog();
        if($param['set']){
            $catalog->addPurchaseToFavorite($param['purchase'], $_SESSION['user']['id']);
            return [
                'stat'    => 1,
                'message' => "Вы подписанны на закупку"
            ];
        }else{
           $catalog->delPurchaseFromFavorite($param['purchase'], $_SESSION['user']['id']);
            return [
                'stat'    => 1,
                'message' => "Вы отписанны от закупки"
            ]; 
        }
    }
}
