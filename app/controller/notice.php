<?php

namespace app\controller;

class notice extends base
{
   public function createOrder($user, $order, $total)
   {
       $user = (new \app\model\users())->getUser($user);
       $this->sendNotice($user,
        "Вы оформили заказ на сайте СП Бутичок",       
        $this->render('notice/createOrder',[
           "order" => $order,
           "user"  => $user
       ]));
   }
   
   public function sendNotice($user, $subject, $text)
   {
       $notice = new \app\model\notice();
       $toEmail = $this->render("notice/emailHeader",[
           'user' => $user,
           'site' => $this->getSite()
       ]) . $text;
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
}
