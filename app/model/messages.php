<?php

namespace app\model;
use \app\model\events;

class messages extends base
{
    public function sendMessage($from, $to, $message)
    {
        $from = intval($from);
        $to   = intval($to);
        if(!$to || !$message){
            throw new Exception("Нет получателя или сообщение пустое!");
        }
        
        $message = [
            'from' => $from,
            'to'   => $to,
            'text' => $message,
            'date' => date("Y-m-d H:i:s"),
            'read' => 0
        ];
        
        $id = $this->db->insert('messages',$message);
        
        $message['id'] = $id;
        
        events::event($to, 'message', $message);
        
        return $id;
    }
    
    public function getCountUnread($user)
    {
        return $this->db->selectCell("select count(*) from messages where `to`=?d && `read`=0", $user);
    }
    
    public function getTimeLastMessage($user)
    {
        //$res = $this->db->selectCell("select `date` from messages where `to`=?d order by `date` DESC limit 1",$user);
        //return strtotime($res);
        return events::getTimeLastEvent($user);
    }
    
    public function getDialogs($filter)
    {
        $limit = ($filter['limit']) ? intval($filter['limit']) : 10;
        $user  = intval($filter['user']);
        $page  = ($filter['page']) ? intval($filter['page']) : 1;
        
        $list  = $this->db->select(
            "SELECT 
                SQL_CALC_FOUND_ROWS DISTINCT (case m.from when $user then m.to else m.from end) as 'user', u.surname, u.name, u.secondname, 
                    img.name as img, img.folder, img.type, 
                    m.date, m.text, m.read
                FROM `messages` as m 
                    left join users as u on u.id = (case m.from when $user then m.to else m.from end)
                    left join images as img on img.parent = 'user' && img.parent_id = (case m.from when $user then m.to else m.from end)
                WHERE 
                    m.from = $user or m.to = $user
                    group by u.id
                 HAVING
                    u.id != $user
                 ORDER by m.date DESC " . $this->createLimit($page, $limit) );
        
        $count = $this->db->selectCell("SELECT FOUND_ROWS()");
        return [
            'list'  => $list,
            'page'  => $page,
            'pages' => ceil($count / $limit),
            'count' => $count,
            'limit' => $limit
        ];
    }
    
    public function getMessages($from, $to, $page=1)
    {
        $limit = 20;
        $from = intval($from);
        $to   = intval($to);
        $list = $this->db->select("select m.* from messages as m "
                . "where m.from in($from,$to) or m.to in ($from,$to) "
                . "order by m.date DESC " . $this->createLimit($page, $limit));
        
        $list = array_reverse($list);
        
        return $list;
    }
}
