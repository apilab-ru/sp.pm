<?php

namespace app\model;

class catalog extends base
{
    
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
            $sql .= " && p.id in(select purchase from purchase_cat where cat in($cats))";
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
    
    public function getPurchase($id)
    {
        $row = $this->db->selectRow("select * from purchase where id=?d", $id);
        return $row;
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
        $this->db->setLogger();
        return $this->db->select("select t.*,t.id as ARRAY_KEY from tags as t, purchase_tag as pt where t.id = pt.tag && pt.purchase=?d",$purchase);
    }
    
    public function getPurchaseOptions($purchase)
    {
        return $this->db->select("select o.name,o.type,o.id as ARRAY_KEY, "
                . " (CASE o.type "
                    . " WHEN 'text' then value_text "
                    . " when 'radio' then value_radio"
                    . " when 'price' then value_price"
                    . " when 'percent' then value_percent"
                . " end) as `value`"
                . " from purchase_options as o, purchase_values as v"
                . " where v.purchase = ?d && o.id = v.option_id", $purchase);
    }
    
    public function getPurchaseStockCats($purchase)
    {
        return $this->db->select("select c.id,c.name from stock_cats as c,stock as s where s.purchase = ?d && s.cat = c.id group by c.id", $purchase);
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
            $sql .= " && s.cat in(" .implode(",", $cats) . ")"; 
        }
        
        if($filter['price_from']){
            $from = intval($filter['price_from']);
            $where .= " && s.price >= $from ";
        }
        
        if($filter['price_to']){
            $to = intval($filter['price_to']);
            $where .= " && s.price >= $to ";
        }
        
        $page  = intval($filter['page']);
        $limit = intval($filter['limit']);
        
        if($where){
            $sql .= " where  " . $where;
        }
        
        $sql .= $this->createOrder($filter['order'], $filter['order_type']);
        
        $sql .= $this->createLimit($page, $limit);
        
        $list = $this->db->select($sql);
        
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
            'count' => $this->getCount(),
            'page'  => $page,
            'limit' => $limit
        ];
    }
    
    public function getListForBasket($list)
    {
        $stocks = $this->db->select("select s.*, s.id as ARRAY_KEY from stock as s where s.id in(?a)", array_keys($list));
        foreach($stocks as $key=>$stock){
            $stocks[$key]['sizes'] = json_decode($stocks[$key]['sizes'],1);
            $stocks[$key]['colors'] = json_decode($stocks[$key]['colors'],1);
        }
        $images = $this->db->select("select * from images where parent='stock' && parent_id in (?a)", array_keys($list));
        foreach($images as $img){
            $stocks[$img['parent_id']]['img'][] = $img;
        }
        
        return $stocks;
    }
    
    public function calcedBasketItems($stocks, $list)
    {
        $groupped = [];
        $pids = [];
        foreach($list as $key=>$set){
            if($stocks[$key]){
                
                $pkey = $stocks[$key]['purchase'];
                
                $pids[ $pkey ] = $pkey;
                
                foreach($set['param'] as $param){
                    $paramKey = $key . json_encode($param);
                    $groupped[ $pkey ]['list'][$paramKey]['stock'] = $stocks[$key];
                    $groupped[ $pkey ]['list'][$paramKey]['count'] ++;
                    $groupped[ $pkey ]['list'][$paramKey]['summ'] = $groupped[ $pkey ]['list'][$paramKey]['count'] * $stocks[$key]['price'];
                    $groupped[ $pkey ]['list'][$paramKey]['param'] = $param;
                }
                
            }
        }
        
        $purchases = $this->getListPurchases(array_keys($pids), true);
        
        $users = new \app\model\users();
        
        foreach($purchases as $id=>$purchase){
            $purchases[$id]['user'] = $users->getUser($purchase['user']);
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
            $sql = "INSERT INTO `purchase_values`(`option_id`, `purchase`, `value_text`, `value_percent`, `value_radio`, `value_price`) VALUES ";
            $add = [];
            $base = array(
                'option_id'     => 0,
                'purchase'      => $id,
                'value_text'    => "NULL",
                'value_percent' => "NULL",
                'value_radio'   => "NULL",
                'value_price'   => "NULL"
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
            $sql .= ",v.value_percent as sbor, p.user ";
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
}
