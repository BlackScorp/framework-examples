<?php

namespace BlackScorp\Movies\Middleware;

use BlackScorp\Movies\Mustache\Template;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

final class MustacheMiddleware implements MiddlewareInterface
{
    public const TEMPLATE_KEY = 'template';
    private \Mustache_Engine $mustache;

    /**
     * @param \Mustache_Engine $mustache
     */
    public function __construct(\Mustache_Engine $mustache)
    {
        $this->mustache = $mustache;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $template = new Template();

        $request = $request->withAttribute(self::TEMPLATE_KEY ,$template);

        $response = $handler->handle($request);

        if($template->getTemplate()){
             $content = $this->mustache->render($template->getTemplate(),$template->getData());
             $response = new Response();
             $response->getBody()->write($content);
        }

        return $response;
    }

}