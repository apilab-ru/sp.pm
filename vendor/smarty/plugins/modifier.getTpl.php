<?php

function smarty_modifier_getTpl($tpl,$fullPatch)
{
    $st = explode("/",$fullPatch);
    unset($st[ count($st)-1 ]);
    $st[] = "$tpl.tpl";
    
    return "app:".implode("/",$st);
}
