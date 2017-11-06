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
    function pagination($args=null)
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
}  
