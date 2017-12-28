<?php

namespace app\controller;

class notice extends base
{
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
       
       $notice->send($user['email'], $subject, $toEmail);
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
}
