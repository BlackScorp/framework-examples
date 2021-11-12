<?php


error_reporting(E_ALL);
ini_set('display_errors','On');

require_once __DIR__.'/vendor/autoload.php';
(Dotenv\Dotenv::createUnsafeImmutable(__DIR__))->load();

$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__.'/config/services.php');
$container = $containerBuilder->build();

$app = $container->get(\Slim\App::class);
require_once  __DIR__.'/config/middlewares.php';
require_once  __DIR__.'/config/routes.php';



return $app;