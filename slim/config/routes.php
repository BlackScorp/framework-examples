<?php


use BlackScorp\Movies\Controller\IndexController;
use BlackScorp\Movies\Controller\SearchController;

$app->get('/', IndexController::class.':indexAction')->setName(IndexController::ROUTE_NAME_INDEX);
$app->get('/wish', IndexController::class.':wishlistAction')->setName(IndexController::ROUTE_NAME_WISH_LIST);
$app->get('/index/add/{id}',IndexController::class.':addAction');
$app->get('/index/add/{id}/{wish}',IndexController::class.':addAction');
$app->get('/errorPage',IndexController::class.':errorAction');
$app->post('/search', SearchController::class);