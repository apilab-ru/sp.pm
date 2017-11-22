<?php
function smarty_modifier_checkVersion($file){
    $time = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
    //pr(date("Y-m-d H:i:s",$time));
    return $file . "?" . $time;
}
?>