<?php

function smarty_function_utils($params)
{
    $util = $params['name'];
    unset($params['name']);
    $name = "\core\utils\\$util";
    if(class_exists($name)){
        return $name::widget($params);
    }else{
       $util[0] = strtoupper($util[0]);
       $name = "\core\utils\\$util"; 
       if(class_exists($name)){
           return $name::widget($params);
       }else{
           dlog('error util not found',$util);
       }
    }
}
