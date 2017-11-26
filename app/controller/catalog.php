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
        
        echo $this->render("catalog/catalog",[
                'cats'     => $catalog->getCats(),
                'discount' => $catalog->getDiscounts(),
                'tags'     => $catalog->getTags(),
                'check'    => $param['cats']
            ]);
        return [
            'struct' => 'catalog'
        ];
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
        
        echo $this->render('catalog/zakupka', [
            'purchase'    => $purchase,
            'organizator' => $organizator,
            'discounts'   => $catalog->getPurchaseDiscount($purchase['id']),
            'options'     => $catalog->getPurchaseOptions($purchase['id']),
            'user'        => $user,
            'cats'        => $catalog->getPurchaseStockCats($purchase['id']),
            'isFavorite'  => $catalog->checkPurchaseFavorite($purchase['id'], $user['id'])
        ]);

        return  [
            "struct" => "zakupha"
        ];
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
    
    public function stockList($args, $param)
    {
        $catalog = new \app\model\catalog();
        $data    = $catalog->getStockByFilter($param);
        $data['basket'] = new \app\model\basket();
        $opts = $catalog->getPurchaseOptions($param['purchase']);
        $data['org'] = $opts[3]['value'];
        echo $this->render('catalog/stockList', $data);
    }
    
    public function updateBasket($args, $param)
    {
        $basket = new \app\model\basket();
        $basket->updateStock($param['stock'], $param['count'], $param['param']);
        $basket->saveBasket();
        return [
            'stat'  => 1,
            'count' => $basket->calcCount()
        ];
    }
    
    public function order($args, $param)
    {
        $basket  = new \app\model\basket();
        $catalog = new \app\model\catalog();
        
        $list      = $basket->getList();
        $stocks    = $catalog->getListForBasket($list);
        
        $groupped  = $catalog->calcedBasketItems($stocks, $list); 
        
        echo $this->render('catalog/order',[
            'groupped' => $groupped
        ]);    
        return [
            "struct" => "order"
        ];
    }
    
    public function payPurchase($args, $param)
    {
        pr($param);
    }
    
    public function purchaseTable($param)
    {
        $widget  = new widget();
        $catalog = new \app\model\catalog();
        
        $data = $catalog->getDataList($param);
        
        return $widget->tableAndFilter(
            $widget->tableByFilter(
                $data,
                [
                    'title'  => 'Закупки',
                    'add'    => '/admin/catalog/addPurchase',
                    'edit'   => "/admin/catalog/editPurchase",
                    'delete' => "/admin/catalog/deletePurchase",
                    "labels" => [
                        "id"         => "id",
                        "name"       => "Название",
                        "user_name"  => "Организатор",
                        "date_create" => [
                            "name" => "Дата создания",
                            "type" => "date"
                        ],
                        "date_stop"  => [
                            "name" => "Дата создания",
                            "type" => "date"
                        ],
                        "active"       => [
                            "name" => "Активна",
                            "type" => "checkbox"
                        ]
                ]]), 
            ""
        );
    }
    
    public function stockTable($param)
    {
        $widget  = new widget();
        $catalog = new \app\model\catalog();
        
        $data = $catalog->getStockByFilter($param,1);
        foreach($data['list'] as $key=>$item){
            if($item['img']){
                $data['list'][$key]['image'] = $item['img'][0];
            }
        }
        
        return $widget->tableAndFilter(
            $widget->tableByFilter(
                $data,
                [
                    'title'  => 'Товары закупок',
                    'add'    => '/admin/catalog/addStock',
                    'edit'   => "/admin/catalog/editStock",
                    'delete' => "/admin/catalog/deleteStock",
                    "labels" => [
                        "id"            => "id",
                        "name"          => "Название",
                        "purchase_name" => "Закупка",
                        "image"         => [
                            "type" => "image",
                            "name" => "Фото"
                        ],
                        "price"         => "Цена"
                ]]), 
            $widget->createFilter([
                'purchase' => [
                    'type' => 'select',
                    'name' => 'Закупка',
                    'list' => $catalog->getListPurchases()
                ]
            ],
            [
                'id'            => 'ID',
                'purchase_name' => "Закупка",
                'price'         => "Цена"
            ],
            $param)
        );
    }
    
    
    public function editStock($param)
    {
        return $this->editStockForm($param['send']['id']);
    }
    
    public function addStock($param)
    {
        return $this->editStockForm();
    }
    
    private function editStockForm($id=null)
    {
        $catalog = new \app\model\catalog();
        if($id){
            $object = $catalog->getStock($id);
            $object['images'] = $catalog->getImagesStock($id);
        }
        $purchases = $catalog->getListPurchases();
        
        
        return $this->render('catalog/formEditStock',[
            'object'    => $object,
            'purchases' => $purchases,
            "cats"      => $catalog->getCatsList()
        ]);
    }
    
    public function addPurchase($param)
    {
        return $this->editPurchaseForm();
    }
    
    public function editPurchase($param)
    {
        return $this->editPurchaseForm($param['send']['id']);
    }
    
    private function editPurchaseForm($objectId=null)
    {
        $catalog = new \app\model\catalog();
        if($objectId){
            $object = $catalog->getPurchase($objectId);
            $object['discounts'] = $catalog->getPurchaseDiscount($objectId);
            $object['options']   = $catalog->getPurchaseOptions($objectId);
            $object['tags']      = $catalog->getPurchaseTags($objectId);
        }
        
        $users   = new \app\model\users();
        
        return $this->render("catalog/formEditPurchase",[
            "object"    => $object,
            "users"     => $users->getOrganizators(),
            "tags"      => $catalog->getTags(),
            "discounts" => $catalog->getDiscounts(),
            "options"   => $catalog->getOptions()
        ]);
    }
    
    public function savePurchase($send)
    {
        $catalog = new \app\model\catalog();
        $id = $catalog->savePurchase($send['send']['form']);
        $catalog->savePurchaseOptions($id, $send['send']['option']);
        return [
            'id'   => $id,
            'stat' => 1
        ];
    }
    
    public function saveStock($send)
    {
        $catalog = new \app\model\catalog();
        $id = $catalog->saveStock(json_decode($send['form'],1), $_FILES['file']);
        return [
            'stat' => 1,
            'id'   => $id
        ];
    }
    
    public function catsEdit()
    {
        $catalog = new \app\model\catalog();
        $cats = $catalog->getTreeCats();
        
        return $this->render('catalog/catsEdit',[
            'cats' => $cats
        ]);
    }
    
    public function catEdit($send)
    {
        $catalog = new \app\model\catalog();
        $cat = $catalog->getCat($send['send']['id']);
        return $this->render('catalog/catEdit',[
            'object' => $cat
        ]);
    }
    
    public function catSave($send)
    {
        $catalog = new \app\model\catalog();
        
        if($_FILES['file']){
            $files = new \app\model\images();
        }
        
        if($files && $send['id']){
            $cat = $catalog->getCat($send['id']);
            if($cat['image']){
                $files->remove($cat['image']);
            }
        }
        
        if($files){
            $file = $files->uploadFile($_FILES['file'], 'other');
            $send['image'] = $files->addImage($file['name'], $file['title'], $file['folder'], 'other', 0, $file['type']);
        }
        
        $send['id'] = $catalog->updateObject('cats', $send);
        
        return [
            'stat'   => 1
        ];
    }
    
    public function catRemove($send)
    {
        $id = $send['send']['id'];
        $catalog = new \app\model\catalog();
        $cat = $catalog->getCat($id);
        if(!$cat){
            throw new \Exception("Ошибка, категория не надена");
        }
        if($cat['image']){
            (new \app\model\images())->remove($cat['image']);
        }
        $catalog->removeCat($id);
        return [
            'stat' => 1
        ];
    }
    
    public function catsListUpdate($send)
    {
        $catalog = new \app\model\catalog();
        $catalog->updateCatList($send['send']['list']);
        return [
            'stat' => 1
        ];
    }
    
    public function tagsTable()
    {
        $widget  = new widget();
        $catalog = new \app\model\catalog();
        
        $data = $catalog->getTagsByFilter($param,1);
        
        return $widget->tableAndFilter(
            $widget->tableByFilter(
                $data,
                [
                    'title'  => 'Тэги',
                    'add'    => '/admin/catalog/editTag',
                    'edit'   => "/admin/catalog/editTag",
                    'delete' => "/admin/catalog/deleteTag",
                    "labels" => [
                        "id"            => "id",
                        "name"          => "Название"
                ]]), 
            ""
        );
    }
    
    public function editTag($send)
    {
        if($send['send']['id']){
            $object = (new \app\model\catalog())->getTag($send['send']['id']);
        }
        return $this->render('catalog/formEditTag',[
            'object' => $object
        ]);
    }
    
    public function saveTag($send)
    {
        $catalog = new \app\model\catalog();
        $id = $catalog->updateobject('tags', $send['send']['form']);
        if($id){
            return [
                'stat' => 1,
                'id'   => $id
            ];
        }
    }
    
    public function deleteTag($send)
    {
        $catalog = new \app\model\catalog();
        $catalog->deleteObject('tags', $send['send']['id']);
    }
    
    public function discountsTable()
    {
        $widget  = new widget();
        $catalog = new \app\model\catalog();
        
        $data = $catalog->getDiscountsByFilter($param,1);
        
        return $widget->tableAndFilter(
            $widget->tableByFilter(
                $data,
                [
                    'title'  => 'Скидки',
                    'add'    => '/admin/catalog/editDiscount',
                    'edit'   => "/admin/catalog/editDiscount",
                    'delete' => "/admin/catalog/deleteDiscount",
                    "labels" => [
                        "id"            => "id",
                        "name"          => "Название"
                ]]), 
            ""
        );
    }
    
    public function editDiscount($send)
    {
        if($send['send']['id']){
            $object = (new \app\model\catalog())->getDiscount($send['send']['id']);
        }
        return $this->render('catalog/formEditDiscount',[
            'object' => $object
        ]);
    }
    
    public function saveDiscount($send)
    {
        $catalog = new \app\model\catalog();
        $id = $catalog->updateobject('discount', $send['send']['form']);
        if($id){
            return [
                'stat' => 1,
                'id'   => $id
            ];
        }
    }
    
    public function deleteDiscount($send)
    {
        $catalog = new \app\model\catalog();
        $catalog->deleteObject('discount', $send['send']['id']);
    }
}
