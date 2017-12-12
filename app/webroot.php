<?php

use Symfony\Component\Routing;
use app\simpleroot;

$w = new simpleroot();
 
$routes = new Routing\RouteCollection();

$routes->add('main', new Routing\Route('/', $w->create('main', 'main', true)));
$routes->add('faq', new Routing\Route('/faq/', $w->create('main', 'faq', true)));
$routes->add('oplata', new Routing\Route('/pay/', $w->create('main', 'oplata', true)));
$routes->add('delivery', new Routing\Route('/delivery/', $w->create('main', 'delivery', true)));

$routes->add('organizator', new Routing\Route('/organizator/{id}/', $w->create('users', 'organizator', true)));

$routes->add('zakupka', new Routing\Route('/zakupka/{id}/', $w->create('catalog', 'zakupka', true)));
$routes->add('catalog', new Routing\Route('/catalog/', $w->create('catalog', 'catalog', true)));
$routes->add('order', new Routing\Route('/order/',  $w->create('catalog', 'order', true)));
$routes->add('orders', new Routing\Route('/orders/',  $w->create('catalog', 'orders', true)));
$routes->add('ordersArchive', new Routing\Route('/orders/archive/',  $w->create('catalog', 'ordersArchive', true)));

$routes->add('orderInfo', new Routing\Route('/order/{id}/',  $w->create('catalog', 'orderInfo', true)));
$routes->add('payment', new Routing\Route('/order/pay/{id}/',  $w->create('catalog', 'payPurchase', true)));
$routes->add("orderCreate", new Routing\Route("/order/create/{stock}/", $w->create('catalog', 'orderCreate', true)));


$routes->add('messagesUser', new Routing\Route('/messages/user/{id}/',  $w->create('messages', 'user', true)));
$routes->add('messagesDialogs', new Routing\Route('/messages/dialogs/',  $w->create('messages', 'dialogs', true)));
$routes->add('messagesNotices', new Routing\Route('/messages/notices/',  $w->create('messages', 'notices', true)));

$routes->add('cabinet', new Routing\Route('/cabinet/', $w->create('users', 'cabinet', true)));

$routes->add('photos', new Routing\Route('/cachephoto/{parent}/{year}/{month}/{file}_{tpl}.{ext}', $w->create('images', 'genCache', false)));

$routes->add('admin', new Routing\Route('/admin/', [
    "controller"    => "admin",
    "action"        => "page",
    "subcontroller" => "admin",
    "subaction"     => "main"
]));
$routes->add('adminPage', new Routing\Route('/admin/{subcontroller}', [
    "controller"    => "admin",
    "action"        => "page",
    "subaction"     => "main"
]));
$routes->add('adminPage', new Routing\Route('/admin/{subcontroller}/{subaction}', $w->create('admin', 'page', false)));
$routes->add('adminAjax', new Routing\Route('/admin/ajax/{subcontroller}/{subaction}', $w->create('admin', 'ajax', false)));

$routes->add('controllerAction', new Routing\Route('/{controller}/{action}/', $w->create('ajax', 'route', false)));
$routes->add('ajax', new Routing\Route('/ajax/{subcontroller}/{subaction}/', $w->create('ajax', 'ajax', false)));

return $routes;
