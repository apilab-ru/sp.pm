<?php
function smarty_modifier_inArray($value,$arr)
{
    if(!$arr){
        return false;
    }
    return in_array($value, $arr);
}
?>