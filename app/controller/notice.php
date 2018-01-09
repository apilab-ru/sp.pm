<?php

namespace app\controller;

class notice extends base
{
   public $limit = 1; 
    
   public function createOrder($user, $orderId, $total)
   {
       $user = (new \app\model\users())->getUser($user);
       $this->sendNotice($user,
        "Вы оформили заказ на сайте СП Бутичок",       
        $this->render('notice/createOrder',[
           "order" => $orderId,
           "user"  => $user
       ]));
   }
   
    public function testNotice()
    {
        set_time_limit(10);
        $user = (new \app\model\users())->getUser(3);
        $notice = new \app\model\notice();
        $toEmail = $this->render("notice/emailHeader",[
           'user' => $user,
           'site' => $this->getSite()
        ]) . "Проверка отправки сообщения";
        
        try{
            $notice->send($user['email'], "Test send", $toEmail);
            pr('ok send');
        }catch(\Exception $e){
            pr('error', $e);
        }
    }
   
    public function createPurchase($purchase)
    {
        $users = new \app\model\users();
        $list = $users->getUsersFromFavorite($purchase['provider']);
        foreach($list as $user){
            $this->sendNotice($user,
                "Появилась новая закупка, от поставщика, на которго вы подписанны",       
                $this->render('notice/createPurchase',[
                   "purchase" => $purchase,
                   "user"     => $user,
                   "site"     => $this->getSite()
                ]
            ));
        }
    }
   
    public function editAccount()
    {
        $notice  = new \app\model\notice();
        $account = $notice->getAccount();
        
        echo $this->render("notice/editAccount",[
            'object' => $account
        ]);
    }
    
    public function saveAccount($param, $send)
    {
        $notice  = new \app\model\notice();
        $notice->saveAccount($send);
        return [
            'stat' => 1
        ];
    }
    
    public function updateStatusOrder($order) 
    {
        $user = (new \app\model\users())->getUser($order['user']);
        $this->sendNotice(
            $user,
            "Статус вашего заказа #" . $order['id'] . " обновлён", 
            $this->render('notice/updateOrder', [
                "order" => $order,
            ])
        );
    }

    public function sendNotice($user, $subject, $text)
    {
       $notice = new \app\model\notice();
       $toEmail = $this->render("notice/emailHeader",[
           'user' => $user,
           'site' => $this->getSite()
       ]) . $text;
       
       (new \app\model\messages)->sendMessage(0, $user['id'], $text);
       
       $notice->shedule($user['email'], $subject, $toEmail);
   }
   
   public function getSite()
   {
       return $_SERVER['HTTP_ORIGIN'];
   }
   
   public function payReport($organizator, $purchase, $user, $order, $num)
   {
       $users = new \app\model\users();
       $text = $this->render('notice/payReport',[
           'order'    => $order,
           'purchase' => $purchase,
           'user'     => $user,
           'num'      => $num,
           'site'     => $this->getSite()
       ]);
       $this->sendNotice($organizator, "Оплата заказа #{$order['id']} закупки #{$purchase['id']} {$purchase['name']}", $text);
   }
   
    public function updatePassUser($user, $pass)
    {
       $user = (new \app\model\users())->getUser($user);
       $this->sendNotice($user,
        "Вы сменили пароль на сайте СП Бутичок",       
        $this->render('notice/updatePass',[
           "pass" => $pass,
           "user" => $user
       ]));
    }
    
    public function cron()
    {
        set_time_limit(30);
        
        $notice = new \app\model\notice();
        $list   = $notice->getSheduled($this->limit);
        
        register_shutdown_function([$this, 'shutdown']);
        
        $this->list = $list;
        foreach($list as $key=>$item){
            try{
                $notice->send($item['email'], $item['subject'], $item['text']);
                $notice->setStat($item['id'], 'send');
            }catch(\Exception $e){
                $notice->setStat($item['id'], 'error', $e->getMessage());
            }
            unset($this->list[$key]);
        }
    }
    
    public function shutdown()
    {
        if($this->list){
            $file = $_SERVER['DOCUMENT_ROOT'] . "/cache/error.txt";
            file_put_contents($file, 'error send ' . print_r($this->list, true));
            $notice = new \app\model\notice();
            dlog('error sended', $this->list);
            foreach($this->list as $item){
               $notice->setStat($item['id'], 'shedule', 'not sended'); 
            }
        }
    }
    
    
}
