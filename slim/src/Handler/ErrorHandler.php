<?php

namespace BlackScorp\Movies\Handler;

;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\App;

final class ErrorHandler
{
    public const ENV_DEV = 'dev';
    private App $app;
    private LoggerInterface $logger;
    private string $environment;

    /**
     * @param App $app
     * @param LoggerInterface $logger
     * @param string $environment
     */
    public function __construct(App $app, LoggerInterface $logger, string $environment)
    {
        $this->app = $app;
        $this->logger = $logger;
        $this->environment = $environment;
    }


    public function __invoke(ServerRequestInterface $request,\Throwable $throwable)
    {
       $this->logger->critical($throwable->getMessage(),['trace'=>$throwable->getTrace()]);
        if($this->environment === self::ENV_DEV){
            throw $throwable;
        }
        $response = $this->app->getResponseFactory()->createResponse(500);

        return $response->withHeader('Location','/errorPage');
    }
}
