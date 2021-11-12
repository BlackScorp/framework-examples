<?php

use BlackScorp\Movies\Service\MovieDBApiProvider;

$dependencies = [];
$dependencies[\Slim\App::class] = Di\factory([\Slim\Factory\AppFactory::class,'createFromContainer']);
$dependencies[\GuzzleHttp\Client::class] = Di\create(\GuzzleHttp\Client::class)->constructor([
    'base_uri'=> Di\env('API_MOVIE_DB_BASE_URL')
]);

$dependencies[MovieDBApiProvider::class] = Di\create(MovieDBApiProvider::class)->constructor(
    Di\get(\GuzzleHttp\Client::class),
    Di\env('API_MOVIE_DB_SECRET')
);

$dependencies['mustache.templateDir'] = __DIR__ . '/../templates';
$dependencies['mustache.fileLoader'] = function(\Psr\Container\ContainerInterface  $container){
    return new Mustache_Loader_FilesystemLoader($container->get('mustache.templateDir'));
};
$dependencies[Mustache_Engine::class] = function (\Psr\Container\ContainerInterface  $container){
    return new Mustache_Engine([
       'loader'  => $container->get('mustache.fileLoader'),
        'partials_loader'=>$container->get('mustache.fileLoader')
    ]);
};

return $dependencies;