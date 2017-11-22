<?php

namespace app\controller;


class widget extends base
{
    
     /*
     *  $args = [
     *      pages => pages, // Кол-во страниц
     *      page  => page, // Текущая страница
     *      count => count //Общее число записей
     *      func  => func // JS функция обработчик клика
     *  ]
     *  return $html;
     */
    public function pagination($args=null)
    {
        $args['delta'] = 5;
        
        if(!$args['limit']){
            $args['limit'] = 10;
        }
        
        if(!$args['pages']){
            if($args['limit'] < $args['count']){
                $args['pages'] = ceil( $args['count'] / $args['limit'] );
            }
        }
        
        return $this->render('widget/pagination',$args);
    }
    
    /*public function table($data)
    {
        return $this->render('widget/table',$data);
    }*/
    
    
    /*
     * [
            'title' => 'Продажа товаров',
            'add' => '/page/payment/editPayment',
            'edit' => "/page/payment/editPayment",
            'delete' => "/page/payment/deletePayment",
            "labels" => [
                "id" => "id",
                "date" => [
                    "type" => "date",
                    "name" => "Дата"
                ],
                "contragent" => "Контрагент",
                "pay" => "Сумма",
                "comment" => "Комментарий"
            ]
        ])
     */
    
    public function tableByFilter($data, $set)
    {
        $set['list']  = $data['list'];
        $set['count'] = $data['count'];
        $set['page']  = $data['page'];
        $set['limit'] = $data['limit'];
        $link         = $_SERVER['REQUEST_URI'];
        if(!strpos($link, "?")){
            $link .= "?filter";
        }
        $set['link']  = preg_replace("/(&page=[0-9]*)/", '', $link) . "&page";
        $set['link']  = str_replace("ajax/", "", $set['link']);
        
        return $this->render('widget/tableByFilter',$set);
    }
    
    public function tableAndFilter($table, $filter)
    {
        return $this->render("widget/tableAndFilter",[
            "table"  => $table,
            "filter" => $filter
        ]);
    }
    
    public function createFilter($opt, $sort, $filter)
    {
        return $this->render("widget/createFilter",[
            'sort'   => $sort,
            'opt'    => $opt,
            'filter' => $filter
        ]);
    }
}  
