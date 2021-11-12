<?php

$app->addMiddleware($container->get(\BlackScorp\Movies\Middleware\MustacheMiddleware::class));