<?php

namespace app\model;

class Base
{
    public function __construct()
    {
        $this->db = \app\app::$db;
    }
    
    public function updateobject($table,$send)
    {
        if ($send['id']) {
            $id = $send['id'];
            unset($send['id']);
            $this->db->update($table, $send, $id);
        } else {
            $id = $this->db->insert($table, $send);
        }
        return $id;
    }
    
    public function deleteObject($table,$id)
    {
        $this->db->query("DELETE from $table where id=?d",$id);
    }
    
    public function createLimit(&$page,&$limit)
    {
        $limit = ($limit) ? $limit : 20;
        $page  = ($page)  ? $page  : 1;
        $start = ($page-1) * $limit;
        return " limit $start,$limit";
    }
    
    public function createOrder($order, $orderType, $defaultOrder="id", $defaultOrderType='DESC')
    {
        $order = $order ? $order : $defaultOrder;
        $orderType = ($orderType) ? $orderType : $defaultOrderType;
        if($orderType == 'DESC'){
            $orderType = 'DESC';
        }else{
            $orderType = 'ASC';
        }
        return " order by $order $orderType";
    }
    
    public function getCount()
    {
        return $this->db->selectCell("SELECT FOUND_ROWS()");
    }
    
    public function setFilter($base, $param)
    {
        foreach($param as $key=>$item){
            $base[$key] = $item;
        }
        return $base;
    }
    
    public function clearForm($form, $aviable)
	{
		foreach($form as $key=>$val){
			if($aviable[$key]){
				
				if(is_array($aviable[$key])){
					if(!in_array($val, $aviable[$key])){
						$form[$key] = $aviable[$key][0];
					}
				}else{
					switch($aviable[$key]){
						case 'text':
						case 'string':
							//$form[$key] = $this->escape_string($val);
							break;
						
						case 'int':
							$form[$key] = intval($val);
							break;
                        
                        case 'float':
                            $form[$key] = floatval($val);
                            break;
						
						case 'json':
							if(!is_string($val)){
								$form[$key] = json_encode($val, JSON_UNESCAPED_UNICODE);
							}
							break;
							
						case 'date':
							$form[$key] = date("Y-m-d",strtotime($val));
							break;
						
						case 'datetime':
							$form[$key] = date("Y-m-d H:i:s",strtotime($val));
							break;
                        
                        case 'password':
                            if($val){
                                $form[$key] = md5($val);
                            }else{
                               unset($form[$key]); 
                            }
                            break;
					}
				}
				
			}else{
				unset($form[$key]);
			}
		}
		return $form;
	}
    
    public function prepareValue($val, $type='text')
    {
        switch($type){
            case 'price':
            case 'percent':
                return floatval($val);
                break;
            
            case 'radio':
                return intval($val);
                break;
            
            default:
                return "'".$this->db->escape($val)."'";
                break;
        }
    }
}