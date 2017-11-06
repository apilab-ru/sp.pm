<?php
function smarty_modifier_img($file, $tpl){
    return "/cachephoto" . $file['folder'] . $file['name'] . "_" . $tpl . "." . $file['type'];
}
?>