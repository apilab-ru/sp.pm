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
    
    public function deleteOrderItem($stock, $param)
    {
        $param = $this->toStringParam($param);
        
        foreach($this->card[$stock]['param'] as $key=>$pr){
            
            $pr = $this->toStringParam($pr);
            
            if($pr == $param){
                unset($this->card[$stock]['param'][$key]);
                $this->card[$stock]['count'] --;
            }
        }
        if($this->card[$stock]['count'] < 1){
            unset($this->card[$stock]);
        }
        $this->saveBasket();
    }
    
    public function orderChange($stock, $param, $change)
    {
        if($change > 0){
            for($i=0; $i<$change; $i++){
                $this->card[$stock]['param'][] = $param;
                $this->card[$stock]['count'] ++;
            }
        }else{
            $param = $this->toStringParam($param);
            $minus = 0;
            foreach($this->card[$stock]['param'] as $key=>$pr){
                $pr = $this->toStringParam($pr);
                if($pr == $param){
                    unset($this->card[$stock]['param'][$key]);
                    $this->card[$stock]['count'] --;
                    $minus --;
                    if($minus == $change){
                        break;
                    }
                }
            }
        }
        if($this->card[$stock]['count'] < 1){
            unset($this->card[$stock]);
        }
        $this->saveBasket();
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
                
                $p =  $this->toStringParam($param);
                $find = 0;
                foreach($this->card[$stock]['param'] as $key=>$pp){
                    if($this->toStringParam($pp) == $p){
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
    
    public function calcSummItem($stock, $count, $purchase, $total)
    {
        $sbor = $purchase['sbor'];
        //Акция при покупке в первые 3 часа
        if($purchase['discounts'][2]){
            if(time() - strtotime($purchase['date_create']) < 10800){
                if($sbor > 12){
                    $sbor = 12;
                }
            }
        }
        //Акция при покупке больше 3000руб
        if($purchase['discounts'][1] && $total>=3000){
            if($sbor > 10){
                $sbor = 10;
            }
        }
        
        $sum = $stock['price'] * $count;
        $sum += ($sum * ($sbor/100));
        return $sum;
    }
    
    public function toStringParam($param)
    {
        if(!$param || (!$param['size'] && !$param['color'])){
            return null;
        }else{
            return json_encode($param);
        }
    }
}
