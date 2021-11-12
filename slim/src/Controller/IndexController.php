<?php

namespace BlackScorp\Movies\Controller;

use BlackScorp\Movies\Middleware\MustacheMiddleware;
use BlackScorp\Movies\Mustache\Template;
use Mustache_Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class IndexController
{


    public function indexAction(ServerRequestInterface $request, ResponseInterface  $response)
    {
        /** @var Template $template */
        $template = $request->getAttribute(MustacheMiddleware::TEMPLATE_KEY);

        $template->setTemplate('index');

        $template->setData([
            'text' => 'Hello World!',
        ]);

        return $response;
    }
}