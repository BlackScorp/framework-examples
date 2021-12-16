<?php

use BlackScorp\Movies\Handler\ErrorHandler;
use BlackScorp\Movies\Mustache\LinkStruct;
use BlackScorp\Movies\Repository\Pdo\PdoVideoRepository;
use BlackScorp\Movies\Repository\VideoRepository;
use BlackScorp\Movies\Service\MovieDBApiProvider;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;


$dependencies = [];
$dependencies[\Slim\App::class] = Di\factory([\Slim\Factory\AppFactory::class,'createFromContainer']);
$dependencies[\GuzzleHttp\Client::class] = Di\create(\GuzzleHttp\Client::class)->constructor([
    'base_uri'=> Di\env('API_MOVIE_DB_BASE_URL')
]);

$dependencies[ErrorHandler::class] = Di\create(ErrorHandler::class)->constructor(
    Di\get(\Slim\App::class),
    Di\get(LoggerInterface::class),
    Di\env('ENV')
);

$dependencies[LoggerInterface::class] = function (){
    return new \Psr\Log\NullLogger();
};

$dependencies[PDO::class] =
    DI\create(PDO::class)->constructor(
        DI\env('DB_DSN'),
        DI\env('DB_USERNAME'),
        DI\env('DB_PASSWORD'),
    [
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

$dependencies[VideoRepository::class] = function (ContainerInterface $container){
  return new PdoVideoRepository($container->get(PDO::class));
};

$dependencies[MovieDBApiProvider::class] = Di\create(MovieDBApiProvider::class)->constructor(
    Di\get(\GuzzleHttp\Client::class),
    Di\env('API_MOVIE_DB_SECRET')
);

$dependencies['mustache.templateDir'] = __DIR__ . '/../templates';
$dependencies['mustache.fileLoader'] = function(ContainerInterface  $container){
    return new Mustache_Loader_FilesystemLoader($container->get('mustache.templateDir'));
};

$dependencies['mustache.helpers']= function(ContainerInterface $container){
    /** @var \Slim\App $app */
    $app = $container->get(\Slim\App::class);

  return [
    'urlFor'=>function (LinkStruct $struct) use($app){
      return $app->getRouteCollector()->getRouteParser()->urlFor(
          $struct->routName,
          $struct->data,
          $struct->queryParams
      );
    }

  ];
};
$dependencies[Mustache_Engine::class] = function (ContainerInterface  $container){
    return new Mustache_Engine([
       'loader'  => $container->get('mustache.fileLoader'),
        'partials_loader'=>$container->get('mustache.fileLoader'),
        'helpers' => $container->get('mustache.helpers')
    ]);
};

return $dependencies;