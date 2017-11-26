<?php

namespace app\model;

class basket extends base
{
    public function __construct()
    {
        parent::__construct();
        if($_SESSION['card']){
            $this->card = $_SESSION['card'];
        }else{
            $this->card = $this->getCard();
        }
        
        //register_shutdown_function([$this,"saveBasket"]);
    }
    
    public function getCard()
    {
        $current = $this->getBdCard($_SESSION['user']['id']);
        $this->card = [];
        foreach($current as $item){
            $this->card[ $item['stock'] ] = [
                'price' => $item['price'],
                'param' => json_decode($item['param'],1),
                'count' => $item['count']
            ];
        }
        $_SESSION['card'] = $this->card;
        return $this->card;
    }
    
    public function getBdCard($user)
    {
        return $this->db->select("select b.*,b.stock as ARRAY_KEY,s.price from user_basket as b, stock as s where user=?d && s.id=b.stock",$user);
    }
    
    public function checkStock($stock)
    {
        return $this->card[$stock];
    }
    
    public function getCount($stock)
    {
        return $this->checkStock($stock)['count'];
    }
    
    public function updateStock($stock, $count, $param)
    {
        if($count < 1){
            unset( $this->card[$stock] );
        }else{
            if($this->card[$stock]['count'] > $count){
                
                $p = json_encode($param);
                $find = 0;
                foreach($this->card[$stock]['param'] as $key=>$pp){
                    if(json_encode($pp) == $p){
                        $find = 1;
                        unset($this->card[$stock]['param'][$key]);
                        $this->card[$stock]['param'] = array_values($this->card[$stock]['param']);
                        break;
                    }
                }
                if(!$find){
                    unset($this->card[$stock]['param'][count($this->card[$stock]['param'])-1]);
                }
                
            }else{
                $this->card[$stock]['param'][] = $param;
            }
            $this->card[$stock]['count'] = $count;
        }
    }
    
    public function calcCount()
    {
        $count = 0;
        foreach($this->card as $stock){
            $count += $stock['count'];
        }
        return $count;
    }
    
    public function saveBasket()
    {
        $_SESSION['card'] = $this->card;
        if(!$_SESSION['user']){
            return [
                'stat' => 1
            ];
        }
        $current = $this->getBdCard($_SESSION['user']['id']);
        
        $delete = [];
        $update = [];
        $insert = [];
        
        foreach($this->card as $stock=>$item){
            if($current[$stock]){
                $item['param'] = ($item['param']) ? "'".json_encode($item['param'], JSON_UNESCAPED_UNICODE) . "'" : "NULL";
                $update[ $current[$stock]['id'] ] = $item; 
            }else{
                $param = ($item['param']) ? "'".json_encode($item['param'], JSON_UNESCAPED_UNICODE) . "'" : "NULL";
                $insert[] = "({$_SESSION['user']['id']}, {$stock}, {$item['count']}, $param)";
            }
        }
        
        foreach($current as $stock=>$it){
            if(!$this->card[$stock]){
                $delete[] = $it['id'];
            }
        }
        
        if($delete){
            $this->db->query("DELETE from user_basket where id in(?a)",$delete);
        }
        
        if($insert){
            $this->db->query("INSERT INTO user_basket (user,stock,count,param) VALUES " . implode(",", $insert));
        }
        
        if($update){
            foreach($update as $id=>$up){
                $this->db->query("UPDATE user_basket set `count`=?d, `param`={$up['param']} where id=?d", $up['count'], $id);
            }
        }
        
        return [
            'stat' => 1
        ];
    }
    
    public function getList()
    {
        return $this->card;
    }
}
