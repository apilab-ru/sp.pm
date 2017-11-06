<?php

namespace app\model;

class catalog extends base
{
    
    public function getMainList()
    {
        return $this->db->select("select * from cats where parent=0");
    }
    
    public function getCats()
    {
        return $this->db->select("select * from cats");
    }
    
    public function getDiscounts()
    {
        return $this->db->select("SELECT *,id as ARRAY_KEY FROM `discount`");
    }
    
    public function getTags()
    {
        return $this->db->select("SELECT *,id as ARRAY_KEY FROM `tags`");
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
        return $this->db->selectRow("select * from purchase where id=?d", $id);
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
        return $this->db->select("select d.* from discount as d, purchase_discount as pd where d.id = pd.discount && pd.purchase=?d",$purchase);
    }
    
    public function getPurchaseOptions($purchase)
    {
        return $this->db->select("select o.name,o.type,"
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
}
