<?php

use Symfony\Component\Routing;
use app\webroot;

$w = new webroot();

 
$routes = new Routing\RouteCollection();

$routes->add('main', new Routing\Route('/', $w->create('main', 'main', true)));

$routes->add('zakupka', new Routing\Route('/zakupka/{id}/', $w->create('catalog', 'zakupka', true)));
$routes->add('oplata', new Routing\Route('/pay/', $w->create('main', 'oplata', true)));
$routes->add('organizator', new Routing\Route('/organizator/{id}/', $w->create('users', 'organizator', true)));

$routes->add('controllerAction', new Routing\Route('/{controller}/{action}/', $w->create('ajax', 'route', true)));
$routes->add('ajax', new Routing\Route('/ajax/{subcontroller}/{subaction}/', $w->create('ajax', 'ajax', true)));

$routes->add('catalog', new Routing\Route('/catalog/', $w->create('catalog', 'catalog', true)));
$routes->add('faq', new Routing\Route('/faq/', $w->create('main', 'faq', true)));

$routes->add('photos', new Routing\Route('/cachephoto/{parent}/{year}/{month}/{file}_{tpl}.{ext}', $w->create('images', 'genCache', true)));

$routes->add('cabinet', new Routing\Route('/cabinet/', $w->create('users', 'cabinet', false)));

$routes->add('adminMain', new Routing\Route('/admin/', $w->create('admin', 'main', true)));
 
return $routes;
