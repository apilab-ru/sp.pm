<?php
function smarty_modifier_checked($set, $data) 
{
    if(gettype($set) == gettype($data)){
        return $set == $data;
    }else{
        if(is_array($data)){
            return in_array($set, $data);
        }
        return false;
    }
}
