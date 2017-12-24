<?php

namespace app\model;

class catalog extends base
{
    public $orderStatuses = [
        'create'   => "Создан", 
        'check'    => "Отмечен что оплачен", 
        'verifed'  => "Оплачен" ,
        'complete' => "Завершен"
    ];
    
    public $statuses = [
        'start'   => 'Старт',
        'cansel'  => 'Отменена',
        'stop'    => 'Стоп',
        'restart' => 'Дозаказ'
    ];

    public function getMainList()
    {
        $list = $this->db->select("select *,id as ARRAY_KEY from cats where parent=0");
        
        $ids = [];
        foreach($list as $item){
            $ids[$item['image']] = $item['id'];
        }
        $images = $this->db->select("select * from images where id in (?a)",array_keys($ids));
        foreach($images as $img){
           $list[ $ids[ $img['id'] ] ]['image'] = $img; 
        }
        
        return $list;
    }
    
    public function getCats()
    {
        return $this->db->select("select * from cats");
    }
    
     public function getTreeCats()
    {
        return $this->db->select("select *,id as ARRAY_KEY, parent as PARENT_KEY from cats");
    }
    
    public function getCat($id)
    {
        $row = $this->db->selectRow("select * from cats where id=?d", $id);
        if($row['image']){
            $row['image'] = $this->db->selectRow("select * from images where id=?d", $row['image']);
        }
        return $row;
    }
    
    public function getCatsList()
    {
        return $this->db->select("select id,id as ARRAY_KEY,name from cats");
    }
    
    public function getDiscounts()
    {
        return $this->db->select("SELECT *,id as ARRAY_KEY FROM `discount`");
    }
    
    public function getDiscount($id)
    {
        return $this->db->selectRow("SELECT * from discount where id=?d",$id);
    }
    
    public function getTags()
    {
        return $this->db->select("SELECT *,id as ARRAY_KEY FROM `tags`");
    }
    
    public $tagsFilter = [
        'limit'      => 10,
        'page'       => 1,
        'order'      => 'id',
        'order_type' => 'DESC'
    ];
    public function getTagsByFilter($filter)
    {
        $sql = "SELECT SQL_CALC_FOUND_ROWS * from tags ";
        
        $filter = $this->setFilter($this->tagsFilter, $filter);
        
        $page  = intval($filter['page']);
        $limit = intval($filter['limit']);
        
        $sql .= $this->createOrder($filter['order'], $filter['order_type']);
        
        $sql .= $this->createLimit($page, $limit);
        
        $list = $this->db->select($sql);
        
        return [
            'list'  => $list,
            'count' => $this->getCount(),
            'page'  => $page,
            'limit' => $limit
        ];
    }
    
    public $discountsFilter = [
        'limit'      => 10,
        'page'       => 1,
        'order'      => 'id',
        'order_type' => 'DESC'
    ];
    public function getDiscountsByFilter($filter)
    {
        $filter = $this->setFilter($this->discountsFilter, $filter);
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS id,name from discount ";
        $page  = intval($filter['page']);
        $limit = intval($filter['limit']);
        
        $sql .= $this->createOrder($filter['order'], $filter['order_type']);
        
        $sql .= $this->createLimit($page, $limit);
        
        $list = $this->db->select($sql);
        
        return [
            'list'  => $list,
            'count' => $this->getCount(),
            'page'  => $page,
            'limit' => $limit
        ];
    }
    
    
    public $filter = [
        'limit'      => 10,
        'page'       => 1,
        'order'      => 'p.id',
        'order_type' => 'DESC'
    ];
    public function getDataList($params)
    {
        $filter = $this->setFilter($this->filter, $params);
        
        if(!in_array($filter['order'],['p.id'])){
            $filter['order'] = 'p.id';
        }
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS p.*,u.name as user_name, "
                .    "GROUP_CONCAT(DISTINCT pd.discount SEPARATOR ',') as discounts, GROUP_CONCAT(DISTINCT pt.tag SEPARATOR ',') as tags "
                . " from users as u, purchase as p "
                . " left join purchase_discount as pd on  pd.purchase = p.id "
                . " left join purchase_tag as pt on pt.purchase = p.id "
                . " where u.id = p.user ";
        
        if($filter['cat']){
            $cats = implode( ",", array_map("intval",$filter['cat']) );
            $sql .= " && p.id in(select DISTINCT purchase from stock where cat in($cats))";
        }
        
        if($filter['status']){
            if($filter['status']['start']){
                $date = date("Y-m-d");
                $sql .= " && p.active = 1 && (p.date_stop >= '$date' || p.date_stop is NULL)";
            }
            if($filter['status']['stop']){
                $date = date("Y-m-d",strtotime("+2 day"));
                $sql .= " && p.date_stop <= '$date'";
            }
        }
        
        if($filter['discount']){
            $ids = implode(",", array_map("intval", $filter['discount']));
            $sql .= " && pd.discount in ($ids) ";
        }
        
        if($filter['tag']){
            $ids = implode(",", array_map("intval", $filter['tag']));
            $sql .= " && pt.tag in ($ids) ";
        }
        
        $sql .= " group by p.id ";
        
        $page  = intval($filter['page']);
        $limit = intval($filter['limit']);
        
        $sql .= $this->createOrder($filter['order'], $filter['order_type']);
        
        $sql .= $this->createLimit($page, $limit);
        
        $list = $this->db->select($sql);
        
        foreach($list as $key=>$item){
            if($item['discounts']){
                $list[$key]['discounts'] = explode(",", $item['discounts']);
            }
        }
        
        foreach($list as $key=>$item){
            if($item['tags']){
                $list[$key]['tags'] = explode(",", $item['tags']);
            }
        }
        
        return [
            'list'  => $list,
            'count' => $this->getCount(),
            'page'  => $page,
            'limit' => $limit
        ];
    }
    
    public $ordersFilter = [
        'limit'      => 10,
        'page'       => 1,
        'order'      => 'o.id',
        'order_type' => 'DESC'
    ]; 
    public function getDataOrders($params)
    {
        $filter = $this->setFilter($this->ordersFilter, $params);
        $sql = "select SQL_CALC_FOUND_ROWS o.*,u.name as user_name, u.surname as user_surname, u.secondname as user_secondame, p.name as purchase_name"
                . " from orders as o, users as u, purchase as p"
                . " where p.id = o.purchase && u.id = o.user ";
        
        $page  = intval($filter['page']);
        $limit = intval($filter['limit']);
        
        if($filter['purchase']){
            $p = intval($filter['purchase']);
            $sql .= " && o.purchase=$p";
        }
        
        if($filter['summ']){
            $p = floatval($filter['summ']);
            $sql .= " && o.summ=$p";
        }
        
        if($filter['user']){
            $p = intval($filter['user']);
            $sql .= " && o.user=$p";
        }
        
        if($filter['orderid']){
            $p = intval($filter['orderid']);
            $sql .= " && o.id = $p";
        }
        
        if($filter['notcomplete']){
            $sql .= " && o.status != 'complete'";
        }
        
        if($filter['status'] && $this->orderStatuses[$filter['status']]){
            $sql .= " && o.status='{$filter['status']}'";
        }
        
        $sql .= $this->createOrder($filter['order'], $filter['order_type']);
        
        $sql .= $this->createLimit($page, $limit);
        
        //$this->db->setLogger($sql);
        
        $list = $this->db->select($sql);
        
        return [
            'list'  => $list,
            'count' => $this->getCount(),
            'page'  => $page,
            'limit' => $limit
        ];
        
    }
    
    public function getPurchase($id)
    {
        $row = $this->db->selectRow("select * from purchase where id=?d", $id);
        return $row;
    }
    
    public function getSborPurchase($id)
    {
        return $this->db->selectCell("select value_percent from purchase_values where purchase=?d && option_id=3",$id);
    }
    
    public function getPurchaseInfo($id)
    {
        $users                 = new \app\model\users();
        $purchase              = $this->getPurchase($id);
        $purchase['discounts'] = $this->getPurchaseDiscount($id);
        $purchase['user']      = $users->getUser($purchase['user']);
        $purchase['sbor']      = $this->getSborPurchase($id);
        return $purchase;
    }
    
    public function addPurchaseToFavorite($purchase, $user)
    {
        return $this->db->insert("purchase_favorite",[
            "purchase" => intval($purchase),
            'user'     => intval($user)
        ]);
    }
    
    public function delPurchaseFromFavorite($purchase, $user)
    {
        return $this->db->query("DELETE from purchase_favorite where user=?d && purchase=?d",
                $user,
                $purchase);
    }
    
    public function checkPurchaseFavorite($purchase, $user)
    {
        return $this->db->selectCell("select id from purchase_favorite where user=?d && purchase=?d", $user, $purchase);
    }
    
    public function getPurchaseDiscount($purchase)
    {
        return $this->db->select("select d.*,d.id as ARRAY_KEY from discount as d, purchase_discount as pd where d.id = pd.discount && pd.purchase=?d",$purchase);
    }
    
    public function getPurchaseTags($purchase)
    {
        return $this->db->select("select t.*,t.id as ARRAY_KEY from tags as t, purchase_tag as pt where t.id = pt.tag && pt.purchase=?d",$purchase);
    }
    
    public function getPurchaseOptions($purchase)
    { 
        $this->db->setLogger();
        $list = $this->db->select("select o.name,o.type,o.id as ARRAY_KEY, o.key, "
                . " (CASE o.type "
                    . " WHEN 'text' then value_text "
                    . " when 'radio' then value_radio"
                    . " when 'price' then value_price"
                    . " when 'percent' then value_percent"
                    . " when 'files' then value_files"
                . " end) as `value`"
                . " from purchase_options as o, purchase_values as v"
                . " where v.purchase = ?d && o.id = v.option_id", $purchase);
        
       
        
        if($list[9]['value']){
           $list[9]['value'] = json_decode($list[9]['value'],1);
           $images = new images();
           $list[9]['value'] = $images->getFilesByIds($list[9]['value']);
        }
        
        return $list;
    }
    
    public function getPurchaseStockCats($purchase)
    {
        $list = $this->db->select("select c.id,c.name from cats as c,stock as s where s.purchase = ?d && s.cat = c.id group by c.id", $purchase);
        return $list;
    }
    
    public $stockFilter = [
        'limit'      => 10,
        'page'       => 1,
        'order'      => 's.id',
        'order_type' => 'DESC'
    ];
    
    public function getStockByFilter($params, $getPurchase = null)
    {
        $filter = $this->setFilter($this->stockFilter, $params);
        
        //$scip = DBSIMPLE_SKIP;
        
        $purchase = intval( $filter['purchase'] );
        
        $sql = "select SQL_CALC_FOUND_ROWS s.*,s.id as ARRAY_KEY ";
        
        if($getPurchase){
            $sql .= " ,p.name as purchase_name ";
        }
        
        $sql .= " from stock as s ";
        
        if($getPurchase){
            $sql .= " left join purchase as p on p.id = s.purchase ";
        }
        
        $where = "";
        
        if($purchase){
            $where .= " s.purchase=$purchase ";
        }
        
        if($filter['cats'] && $filter['cats'][0]!=0){
            $cats = array_map("intval", $filter['cats']);
            if($where){
                $where .= " && ";
            }
            $where .= " s.cat in(" .implode(",", $cats) . ")"; 
        }
        
        if($filter['price_from']){
            $from = intval($filter['price_from']);
            if($where){
                $where .= " && ";
            }
            $where .= " s.price >= $from ";
        }
        
        if($filter['price_to']){
            $to = intval($filter['price_to']);
            if ($where) {
                $where .= " && ";
            }
            $where .= " s.price <= $to ";
        }
        
        $page  = intval($filter['page']);
        $limit = intval($filter['limit']);
        
        if($where){
            $sql .= " where  " . $where;
        }
        
        $sql .= $this->createOrder($filter['order'], $filter['order_type']);
        
        $sql .= $this->createLimit($page, $limit);
        
        $list = $this->db->select($sql);
        $count  = $this->getCount();
        $ids = array_keys($list);
        
        $images = $this->db->select("select * from images where parent='stock' && parent_id in (?a)", $ids);
        
        foreach($images as $img){
            $list[$img['parent_id']]['img'][] = $img;
        }
        
        foreach($list as $key=>$item){
            $list[$key]['sizes']  = json_decode($item['sizes'],1);
            $list[$key]['colors'] = json_decode($item['colors'],1);
        }
        
        return [
            'list'  => $list,
            'count' => $count,
            'page'  => $page,
            'limit' => $limit
        ];
    }
    
    public function getListForBasket($ids)
    {
        $stocks = $this->db->select("select s.*, s.id as ARRAY_KEY from stock as s where s.id in(?a)", $ids);
        foreach($stocks as $key=>$stock){
            $stocks[$key]['sizes'] = json_decode($stocks[$key]['sizes'],1);
            $stocks[$key]['colors'] = json_decode($stocks[$key]['colors'],1);
        }
        $images = $this->db->select("select * from images where parent='stock' && parent_id in (?a)", $ids);
        foreach($images as $img){
            $stocks[$img['parent_id']]['img'][] = $img;
        }
        
        return $stocks;
    }
    
    public function calcedBasketItems($stocks, $list)
    {
        $groupped = [];
        $pids = [];
        
        $basket = new basket();
        
        foreach($list as $key=>$set){
            if($stocks[$key]){
                
                $pkey = $stocks[$key]['purchase'];
                
                $pids[ $pkey ] = $pkey;
                
                foreach($set['param'] as $param){
                    $paramKey = $key . $basket->toStringParam($param);
                    $groupped[ $pkey ]['list'][$paramKey]['stock'] = $stocks[$key];
                    $groupped[ $pkey ]['list'][$paramKey]['count'] ++;
                    $groupped[ $pkey ]['list'][$paramKey]['summ'] = $groupped[ $pkey ]['list'][$paramKey]['count'] * $stocks[$key]['price'];
                    $groupped[ $pkey ]['list'][$paramKey]['param'] = $param;
                }
                
            }
        }
        
        $purchases = array();
        
        foreach($pids as $id){
            $purchase         = $this->getPurchaseInfo($id);
            $purchases[$id]   = $purchase;
        }
        
        foreach($groupped as $pur=>$pdata){
            $total = 0;
            $sbor = $purchases[$pur]['sbor'];
            $groupped[$pur]['purchase'] = $purchases[$pur];
            foreach($pdata['list'] as $key=>$item){
                $groupped[$pur]['list'][$key]['sbor'] = ($sbor / 100) * $item['summ'];
                $groupped[$pur]['list'][$key]['itog'] += $item['summ'] + $groupped[$pur]['list'][$key]['sbor'];
                $total += $groupped[$pur]['list'][$key]['itog'];
            }
            $groupped[$pur]['total'] = $total;
        }
        
        return $groupped;
    }
    
    public function calcOrderGroup($stocks, $list)
    {
        $groupped = [];
        foreach($list as $item){
            $stock = $stocks[$item['stock']];
            $pr    = $stock['purchase'];
            $item['stock'] = $stock;
            $groupped[$pr]['list'][] = $item;
        }
        
        foreach($groupped as $pd=>$group){
            $groupped[$pd]['purchase'] = $this->getPurchaseInfo($pd);
            $groupped[$pd]['total'] = 0;
            foreach($group['list'] as $item){
                $groupped[$pd]['total'] += $item['summ'];
            }
        }
        
        return $groupped;
    }
    
    public function getOptions()
    {
        return $this->db->select("select *,id as ARRAY_KEY from purchase_options");
    }
    
    public function updateListTags($id, $tags)
    {
        $this->db->query("DELETE from purchase_tag where purchase=?d",$id);
        if($tags){
            $sql = "INSERT INTO `purchase_tag`(`purchase`, `tag`) VALUES ";
            $add = [];
            foreach($tags as $tag){
                $tag = intval($tag);
                $add[] = "($id, $tag)";
            }
            $this->db->query($sql . implode(",",$add));
        }
    }
    
    public function updateListDiscounts($id, $discounts)
    {
        $this->db->query("DELETE from purchase_discount where purchase=?d",$id);
        if($discounts){
            $sql = "INSERT INTO `purchase_discount`(`purchase`, `discount`) VALUES ";
            $add = [];
            foreach($discounts as $discount){
                $discount = intval($discount);
                $add[] = "($id, $discount)";
            }
            $this->db->query($sql . implode(",",$add));
        }
    }
    
    public function savePurchase($send)
    {
        if(!$send['active']){
            $send['active'] = 0;
        }
        $tags = $send['tags'];
        unset($send['tags']);
        
        $discounts = $send['discounts'];
        unset($send['discounts']);
        
        $id = $this->updateobject('purchase', $send);
        
        if(!$id){
            throw new \Exception("Ошибка сохранения");
        }
        $this->updateListTags($id, $tags);
        $this->updateListDiscounts($id, $discounts);
        return $id;
    }
    
    public function savePurchaseOptions($id, $options)
    {
        $aviable = $this->getOptions();
        $this->db->query("DELETE from purchase_values where purchase=?d",$id);
        if($options){
            $sql = "INSERT INTO `purchase_values`(`option_id`, `purchase`, `value_text`, `value_percent`, `value_radio`, `value_price`, `value_files`) VALUES ";
            $add = [];
            $base = array(
                'option_id'     => 0,
                'purchase'      => $id,
                'value_text'    => "NULL",
                'value_percent' => "NULL",
                'value_radio'   => "NULL",
                'value_price'   => "NULL",
                'value_files'   => "NULL"
            );
            foreach($options as $opt=>$val){
                if($val!=""){
                    $insert = $base;
                    $opt = intval($opt);
                    $insert['option_id'] = $opt;
                    $insert['value_' . $aviable[$opt]['type']] = $this->prepareValue($val, $aviable[$opt]['type']);
                    $add[] = "(". implode(",", $insert) .")";
                }
            }
            $this->db->query( $sql . implode(",", $add) );
        }
    }
    
    public function getListPurchases($ids=null,$sbor=null)
    {
        if($ids){
            $where = " where p.id in (" .implode(",", $ids) . ")";
        }
        $sql = "select p.id,p.id as ARRAY_KEY,p.name ";
        
        if($sbor){
            $sql .= ",v.value_percent as sbor, p.user, p.status, p.date_stop ";
        }
        
        $sql .= "from purchase as p ";
        
        if($sbor){
            $sql .= " left join purchase_values as v on v.purchase = p.id && v.option_id = 3 ";
        }
        
        $sql .= $where;
        
        return $this->db->select($sql);
    }
    
    public function getStock($id)
    {
        $row = $this->db->selectRow("select s.* from stock as s where s.id = ?d",$id);
        $row['images'] = $this->db->select("select * from images where parent='stock' && parent_id=?d",$id);
        $row['sizes']  = json_decode($row['sizes'],1);
        $row['colors'] = json_decode($row['colors'],1);
        return $row;
    }
    
    public function updateListStockPhotos($stock, $files)
    {
        $where = "";
        if($files){
            $files = array_map("intval", $files);
            $where .= " && id not in(" .implode(",", $files).")";
        }
        $files = $this->db->select("select * from images where parent='stock' && parent_id=?d" . $where, $stock);
        if($files){
            $images = new \app\model\images();
            foreach($files as $file){
                $images->removePhotoFile($file['folder'], $file['name'], $file['type']);
                $images->removePhoto($file['id']);
            }
        }
    }
    
    public function getImagesStock($id)
    {
        return $this->db->select("select * from images where parent='stock' && parent_id=?d",$id);
    }
    
    public $stock = [
        'id'          => 'int',
        'cat'         => 'int',
        'name'        => 'string',
        'purchase'    => 'int',
        'description' => 'text',
        'price'       => 'float',
        'sizes'       => 'json',
        'colors'      => 'json'
    ];
    
    public function saveStock($form, $newFiles)
    {
        $files = $form['files'];
        
        $form = $this->clearForm($form, $this->stock);
        
        $id = $this->updateObject('stock', $form);
        if(!$id){
            throw new Exception("Ошибка сохранения");
        }
        $this->updateListStockPhotos($id, $files);
        
        $images = new \app\model\images();
        $files = $images->getFiles($newFiles);
        foreach($files as $file){
            $file = $images->uploadFile($file,'stock');
            $images->addImage($file['name'], $file['name'], $file['folder'], 'stock', $id, $file['type']);
        }
    }
    
    public function removeCat($id)
    {
        $this->db->query("UPDATE cats SET parent=0 where parent=?d", $id);
        $this->db->query("DELETE from cats where id=?d", $id);
    }
    
    public function updateCatList($list)
    {
        return $this->updateTree('cats', $list);
    }
    
    public function getTag($id)
    {
        return $this->db->selectRow("select * from tags where id=?d",$id);
    }
    
    public function addToOrder($stock, $purchase, $count, $sum, $color, $size, $user, $orderId=0)
    {
        $sql = "INSERT INTO `basket_stocks`(`stock`,`purchase`, `count`, `summ`, `color`, `size`, `user`, `order_id`) VALUES ";
        $stock = (int)$stock;
        $count = (int)$count;
        $purchase = (int)$purchase;
        $sum   = floatval($sum);
        $color = ($color) ? ("'". $this->db->escape($color) ."'") : "NULL";
        $size  = ($size) ? ("'". $this->db->escape($size) ."'") : "NULL";
        $user  = intval($user);
        $orderId = (int)$orderId;
        $sql .= "($stock, $purchase, $count, $sum, $color, $size, $user, $orderId)";
        $id = $this->db->query($sql);
        if(!$id){
            dlog('error sql', $sql);
            throw new \Exception("Ошибка оформления заказа");
        }
        return $id;
    }
    
    public function getOrderedList($user, $purchase=null, $order=0)
    {
        return $this->db->select("select *,id as ARRAY_KEY from basket_stocks where user=?d {&& purchase=?d} && order_id=?d", $user, ($purchase) ? $purchase : DBSIMPLE_SKIP, $order);
    }
    
    public function getOrderStocks($orderId)
    {
        $sql = 
            "SELECT b.id, b.count, b.summ, b.color, b.size, b.stock, s.name, s.sizes, s.colors
                FROM stock as s, basket_stocks as b  
                where s.id = b.stock && b.order_id =?d";
        
        $list = $this->db->select($sql, $orderId);
        foreach($list as $key=>$item){
            $list[$key]['colors'] = json_decode($item['colors'],1);
            $list[$key]['sizes']  = json_decode($item['sizes'],1);
        }
        return $list;
    }
    
    public function deleteOrderItemsPurchase($purchase, $user)
    {
        $this->db->query("DELETE from basket_stocks where user=?d && purchase=?d", $user, $purchase);
    }
    
    public function calcSummList($list)
    {
        $total = 0;
        foreach($list as $item){
            $total += $item['summ'];
        }
        return $total;
    }
    
    public function prepareOrder($user, $purchase)
    {
        return $this->db->insert('orders',[
            'user'     => $user,
            'purchase' => $purchase,
            'summ'     => 0
        ]);
    }
    
    public function  updateOrder($id, $total)
    {
        $this->db->query('UPDATE `orders` SET ?a where id=?d ',[
            'summ'     => $total,
        ], $id);
        
        $order = $this->getOrder($id);
        
        $notice = new \app\controller\notice();
        $notice->createOrder($order['user'], $id, $total);
        
        return $order;
    }
    
    public function updateStatusOrder($id, $status)
    {
        $order = $this->getOrder($id);
        
        if($order['status'] != $status){
            
            $this->db->query('UPDATE `orders` SET ?a where id=?d ', [
                'status' => $status,
            ], $id);
             
            $order['status'] = $this->orderStatuses[$status];
            
            $notice = new \app\controller\notice();
            $notice->updateStatusOrder($order);
        }else{
            throw new \Exception("Данные не изменены");
        }
    }

    public function addOrder($user, $purchase, $total)
    {
        $id =  $this->db->insert('orders',[
            'user'     => $user,
            'summ'     => $total,
            'purchase' => $purchase
        ]);
        
        if(!$id){
            throw new Exception("Ошибка создания заказа");
        }
        
        $notice = new \app\controller\notice();
        $notice->createOrder($user, $id, $total);
        
        return $id;
    }
    
    public function addBasketStockToOrder($ids, $order)
    {
        $this->db->query("UPDATE basket_stocks SET order_id=?d where id in(?a)", $order, $ids);
    }
    
    public function getOrderPurchaseUser($user, $purchase)
    {
        return $this->db->selectCell("select id from orders where user=?d && purchase=?d", $user, $purchase);
    }
    
    public function getOrder($id)
    {
        return $this->db->selectRow("select * from orders where id=?d",$id);
    }
    
    public function setStatusOrder($order, $status)
    {
        $this->db->update('orders',[
            'status'      => $status,
            'date_change' => date("Y-m-d H:i:s")
        ], $order);
    }
    
    public function deletePurchase($id)
    {
        //ODO delete options and stocks
        $this->deleteObject('purchase', $id);
    }
    
    public function deleteStock($id)
    {
        $this->deleteObject('stock', $id);
    }
    
    public function getTotalPurchase($id)
    {
        return $this->db->selectRow("SELECT SUM(`count`) as 'count', SUM(`summ`) as 'summ' FROM `basket_stocks` WHERE purchase = ?d group by purchase", $id);
    }
}
