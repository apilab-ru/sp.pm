<?php
function smarty_modifier_inArray($value,$arr)
{
    if(!$arr){
        return false;
    }
    if(is_array($arr)){
        return in_array($value, $arr);
    }else{
        return $value == $arr;
    }
}
?>