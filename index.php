<?php
session_start();
include "app/autoloader.php";
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
 
$request = Request::createFromGlobals();
$routes = include __DIR__.'/webroot.php';
 
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

try{
    $res = $matcher->match($request->getPathInfo());
}catch (Exception $e) {
    //header("Location: /view/404.html");
    die("Нет такой страницы");
}

(new app\app(include "config.php",include "acess.php") )->run( $res, $_REQUEST );