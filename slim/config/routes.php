<?php


use BlackScorp\Movies\Controller\IndexController;
use BlackScorp\Movies\Controller\SearchController;

$app->get('/', IndexController::class.':indexAction');
$app->get('/index/add/{id}',IndexController::class.':addAction');
$app->post('/search', SearchController::class);