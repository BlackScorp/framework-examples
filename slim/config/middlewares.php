<?php
/** @var \Slim\App $app */

$app->addMiddleware($container->get(\BlackScorp\Movies\Middleware\MustacheMiddleware::class));
$errorMiddleware = $app->addErrorMiddleware(true,true,true);
$errorMiddleware->setDefaultErrorHandler($container->get(\BlackScorp\Movies\Handler\ErrorHandler::class));