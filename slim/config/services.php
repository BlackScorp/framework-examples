<?php
$dependencies = [];
$dependencies[\Slim\App::class] = Di\factory([\Slim\Factory\AppFactory::class,'createFromContainer']);

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