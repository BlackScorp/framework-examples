<?php


$app->get('/',\BlackScorp\Movies\Controller\IndexController::class.':indexAction');
$app->post('/search',\BlackScorp\Movies\Controller\SearchController::class);