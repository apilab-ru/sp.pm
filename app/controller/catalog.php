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
            'isFavorite'  => $catalog->checkPurchaseFavorite($purchase['provider'], $user['id']),
            'total'       => $catalog->getTotalPurchase($purchase['id'])
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
        $param['limit'] = 12;
        $data    = $catalog->getStockByFilter($param);
        $data['purchase'] = intval($param['purchase']);
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
        $stocks    = $catalog->getListForBasket(array_keys($list));
        $groupped  = $catalog->calcedBasketItems($stocks, $list); 
        
        $ordered  = $catalog->getOrderedList($_SESSION['user']); 
        $ids = [];
        foreach($ordered as $order){
            $ids[ $order['stock'] ] = $order['stock'];
        }
        $stocks      = $catalog->getListForBasket($ids);
        $orderGroups = $catalog->calcOrderGroup($stocks, $ordered);
        
        echo $this->render('catalog/order',[
            'selected'    => 'order',
            'content'     => $this->render('catalog/orderMain',[
                'groupped'    => $groupped,
                'orderGroups' => $orderGroups,
                'statuses'    => $catalog->statuses,
            ])
        ]);    
        return [
            "struct" => "order"
        ];
    }
    
    public function orders($param, $args)
    {
        $catalog = new \app\model\catalog();
        $widget  =  new \app\controller\widget();
        
        $data = $catalog->getDataOrders([
            'user'        => $_SESSION['user']['id'],
            'notcomplete' => 1,
            'limit'       => 10,
            'page'        => $args['page']
        ]);
        
        $data['statuses']   = $catalog->orderStatuses;
        $data['link']       = $widget->prepeareLink($_SERVER['REQUEST_URI']);
        $data['pagination'] = $widget->pagination($data);
        
        echo $this->render('catalog/order',[
            'selected'    => 'orders',
            'content'     => $this->render('catalog/orders', $data),
        ]);
        
        return ["struct" => "orders"];
    }
    
    public function ordersArchive()
    {
        $catalog = new \app\model\catalog();
        $widget  =  new \app\controller\widget();
        
        $data = $catalog->getDataOrders([
            'user'   => $_SESSION['user']['id'],
            'status' => 'complete',
            'limit'  => 10,
            'page'   => $args['page']
        ]);
        
        $data['statuses']   = $catalog->orderStatuses;
        $data['link']       = $widget->prepeareLink($_SERVER['REQUEST_URI']);
        $data['pagination'] = $widget->pagination($data);
        
        echo $this->render('catalog/order',[
            'selected'    => 'archive',
            'content'     => $this->render('catalog/orders', $data)
        ]);
        
        return ["struct" => "orders/archive"];
    }
    
    public function orderInfo($args)
    {
        $catalog = new \app\model\catalog();
        $order   = $catalog->getOrder($args['id']);
        if(!$order || $order['user'] != $_SESSION['user']['id']){
            echo "<div class='message-single'><div class='error message'>Ошибка! Заказ не найден!</div></div>";
            return;
        }
        $list = $catalog->getOrderedList($_SESSION['user']['id'], null, $order['id']);
        $stocks    = $catalog->getListForBasket(array_map(function($it){
            return $it['stock'];
        },$list));
        foreach($list as $key=>$item){
            $list[$key]['stock'] = $stocks[$item['stock']];
        }
        $purchase = $catalog->getPurchaseInfo(reset($list)['purchase']);
        echo $this->render("catalog/orderId",[
            'order'       => $order,
            'purchase'    => $purchase,
            'list'        => $list
        ]);
    }

    public function sendPayReport($args, $send)
    {
        //($send);
        $catalog = new \app\model\catalog();
        $order = $catalog->getOrder($send['order']);
        if(!$order || $order['user'] != $_SESSION['user']['id']){
            throw new \Exception("Не найден заказ");
        }
        $purchase = $catalog->getPurchaseInfo($order['purchase']);
        $notice = new notice();
        
        $catalog->setStatusOrder($order['id'],'check');
        
        $notice->payReport($purchase["user"],$purchase, $_SESSION['user'], $order, $send['num']);
        return [
            'stat' => 1
        ];
    }
    
    public function payPurchase($args, $param)
    {
        $catalog  = new \app\model\catalog();
        $purchase = $catalog->getPurchaseInfo($args['id']);
        
        if(!$purchase || !$purchase['active'] || $purchase['status'] != 'stop'){
            $error = "purchase";
        }
        
        if(!$error){
            $list = $catalog->getOrderedList($_SESSION['user']['id'], $args['id']);
            if($list){
                $total = $catalog->calcSummList( $list );
                $order = $catalog->addOrder($_SESSION['user']['id'], $args['id'], $total);
                $catalog->addBasketStockToOrder( array_keys($list), $order );
            }else{
                $order = $catalog->getOrderPurchaseUser($_SESSION['user']['id'], $args['id']);
                $error = 'list';
            }
        }
        
        echo $this->render("catalog/payPurchase",[
            'error'    => $error,
            'purchase' => $purchase,
            'order'    => $order
        ]);
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
                            "name" => "Дата стопа",
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
    
    public function ordersTable($param)
    {
        $widget  = new widget();
        $catalog = new \app\model\catalog();
        $data    = $catalog->getDataOrders($param);
        
        return $widget->tableAndFilter(
            $widget->tableByFilter(
                $data,
                [
                    'title'  => '/Заказы',
                    'add'    => '/admin/catalog/editOrder',
                    'edit'   => "/admin/catalog/editOrder",
                    //'delete' => "/admin/catalog/deleteOrder",
                    "labels" => [
                        "id"               => "id",
                        "purchase"         => "#",
                        "purchase_name"    => "Закупка",
                        "summ"             => "Сумма",
                        "user_surname"     => "Фамиля",
                        "user_name"        => "Имя",
                        "user_secondname"  => "Отчество",
                        "date" => [
                            "name" => "Создан",
                            "type" => "date"
                        ],
                        "date_change" => [
                            "name" => "Изменён",
                            "type" => "date"
                        ],
                        "status" => [
                            "name" => "Статус",
                            "type" => "select",
                            "data" => $catalog->orderStatuses
                        ]
                    ]
                ]
            ), 
            $widget->createFilter([
                'purchase' => [
                    'type' => 'select',
                    'name' => 'Закупка',
                    'list' => $catalog->getListPurchases()
                ],
                'summ' => [
                    "type" => "text",
                    "name" => "Сумма"
                ]
            ],
            [
                'id'            => 'ID',
                'purchase_name' => "Закупка",
                'date'          => "Создан",
                'date_change'   => "Изменён"
            ],
            $param)
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
    
    public function deletePurchase($param)
    {
        $catalog = new \app\model\catalog();
        $catalog->deletePurchase($param['send']['id']);
    }
    
    public function deleteStock($param)
    {
        $catalog = new \app\model\catalog();
        $catalog->deleteStock($param['send']['id']);
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
    
    
    public function editOrder($args)
    {
        $catalog = new \app\model\catalog();
        $order = $catalog->getOrder($args['send']['id']);
        $list = $catalog->getOrderStocks($order['id']);
        
        return $this->render('catalog/formEditOrder',[
            'object'   => $order,
            'list'     => $list,
            'statuses' => $catalog->orderStatuses,
            'users'    => (new \app\model\users())->getSimpleList()
        ]);
    }
    
    public function updateOrder($args)
    {
        $catalog = new \app\model\catalog();
        
        $send = $args['send']['form'];
        
        $catalog->updateStatusOrder($send['id'], $send['status']);
        
        return [
            'stat'  => 1
        ];
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
            $object              = $catalog->getPurchase($objectId);
            $object['discounts'] = $catalog->getPurchaseDiscount($objectId);
            $object['options']   = $catalog->getPurchaseOptions($objectId);
            $object['tags']      = $catalog->getPurchaseTags($objectId);
        }
        
        $users     = new \app\model\users();
        
        $providers = new \app\model\providers();
        
        return $this->render("catalog/formEditPurchase",[
            "object"    => $object,
            "users"     => $users->getOrganizators(),
            "tags"      => $catalog->getTags(),
            "discounts" => $catalog->getDiscounts(),
            "options"   => $catalog->getOptions(),
            "statuses"  => $catalog->statuses,
            "providers" => $providers->providersData(['limit'=>1000])['list']
        ]);
    }
    
    public function savePurchase($send)
    {
        $catalog = new \app\model\catalog();
        
        $send = json_decode($send['form'],1);
        
        $images = new \app\model\images();
        $files = $images->getFiles($_FILES['file']);
        
        $id = $catalog->savePurchase($send['form']);
        $filesIds = $send['option'][9];
        if(!$filesIds){
            $filesIds = [];
        }
        
        foreach($files as $file){
            $file = $images->uploadFile($file,'other');
            $filesIds[] = $images->addImage($file['name'], $file['name'], $file['folder'], 'other', 0, $file['type']);
        }
        
        $send['option'][9] = json_encode($filesIds);
        
        $catalog->savePurchaseOptions($id, $send['option']);
        
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
    
    public function deleteOrderItem($args, $send)
    {
        $basket = new \app\model\basket();
        $basket->deleteOrderItem($send['stock'], $send['param']);
        return ['stat'=>1];
    }
    
    public function orderChange($args, $send)
    {
        $basket = new \app\model\basket();
        $basket->orderChange($send['stock'], $send['param'], $send['change']);
        return ['stat'=>1];
    }
    
    public function orderCreate($args, $send)
    {
        $basket  = new \app\model\basket();
        $list    = $basket->getList();
        $catalog = new \app\model\catalog();
        
        $stocks = $catalog->getListForBasket(array_keys($list));
        
        $groupped  = $catalog->calcedBasketItems($stocks, $list); 
        
        $group = $groupped[ $send['id'] ];
        
        if($group['purchase']['status'] == 'cansel' || $group['purchase']['active'] == 0){
            throw new \Exception("Ошибка! Закупка неактивна!");
        }
        
        $orderId = $catalog->prepareOrder($_SESSION['user']['id'], $group['purchase']['id']);
        
        $total = 0;
        
        foreach($group['list'] as $item){
            
            $summ = $basket->calcSummItem($item['stock'], $item['count'], $group['purchase'], $group['total']);
            $total += $summ;
            
            $catalog->addToOrder(
                $item['stock']['id'], 
                $group['purchase']['id'],
                $item['count'], 
                $summ,
                $item['param']['color'],
                $item['param']['size'],
                $_SESSION['user']['id'],
                $orderId
            );
            $basket->orderChange($item['stock']['id'], $item['param'], -1 * $item['count']);
        }
        
        $catalog->updateOrder($orderId, $total);
        
        $basket->saveBasket();
        
        return [
            'stat'  => 1,
            'order' => $orderId
        ];
    }
    
    public function canselOrder($args, $send)
    {
        $basket  = new \app\model\basket();
        $catalog = new \app\model\catalog();
        
        $ordered  = $catalog->getOrderedList($_SESSION['user'], $send['id']); 
        
        $ids = [];
        foreach($ordered as $order){
            $ids[ $order['stock'] ] = $order['stock'];
        }
        $stocks    = $catalog->getListForBasket($ids);
        $groupped  = $catalog->calcOrderGroup($stocks, $ordered); 
        $group     = $groupped[ $send['id'] ];
        
        foreach($group['list'] as $item){
            $basket->orderChange($item['stock']['id'], [
                'color' => $item['color'],
                'size'  => $item['size']
            ], $item['count']);
        }
        
        $basket->saveBasket();
        
        $catalog->deleteOrderItemsPurchase($send['id'], $_SESSION['user']);
        
        return [ 'stat' => 1 ];
        //return $send['id'];
    }
    
    public function providersTable($param)
    {
        $widget    = new widget();
        $providers = new \app\model\providers();
        
        $data = $providers->providersData($param);
        
        return $widget->tableAndFilter(
            $widget->tableByFilter(
                $data,
                [
                    'title'  => 'Поставщики',
                    'add'    => '/admin/admin/editProvider',
                    'edit'   => "/admin/admin/editProvider",
                    'delete' => "/admin/admin/deleteProvider",
                    "labels" => [
                        "id"            => "id",
                        "name"          => "Название"
                ]]), 
            ""
        );
    }
}
