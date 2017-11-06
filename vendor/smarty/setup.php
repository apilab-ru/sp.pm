<?php

define(SMARTY_DIR, __DIR__."/"); // указываем путь до библиотеки Smarty
require_once __DIR__.'/Smarty.class.php';

function appGetTemplate($tpl_name, &$tpl_source, $smarty) {
    $file = $_SERVER['DOCUMENT_ROOT'] . "/view/" . $tpl_name . '.tpl';
    if(file_exists($file)){
        $tpl_source = file_get_contents($file);
    }else{
        $tpl_source = '<div  class="JQDblock alert alert-danger" myid="{$block.id}"> Шаблон '.$tpl_name.' не найден </div>';
    }
    return true;
}

function appGetTimestamp($tpl_name, &$tpl_timestamp, $smarty) {
    $file = $_SERVER['DOCUMENT_ROOT'] . "/view/" . $tpl_name . '.tpl';
    if(file_exists($file)){
        $tpl_timestamp = filemtime ($file);
    }
    return true;
}

function appGetSecure($tpl_name, $smarty) {
    // предполагаем, что шаблоны безопасны
    return true;
}

function appGetTrusted($tpl_name, &$smarty) {
    // не используется для шаблонов
}

function initSmarty() {

    $sm = new Smarty();
    $sm->template_dir = $_SERVER['DOCUMENT_ROOT'] ;
    $sm->compile_dir  = $_SERVER['DOCUMENT_ROOT'] . '/cache/';
    $sm->cache_dir    = $_SERVER['DOCUMENT_ROOT'] . '/cache/';
    $sm->caching      = false;

    $sm->registerResource("app",[
        "appGetTemplate",
        "appGetTimestamp",
        "appGetSecure",
        "appGetTrusted"
    ]);

    return $sm;
}

?>