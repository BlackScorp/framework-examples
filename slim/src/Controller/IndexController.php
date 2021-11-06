<?php

namespace BlackScorp\Movies\Controller;

use Mustache_Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class IndexController
{
    private Mustache_Engine $mustache;

    /**
     * @param Mustache_Engine $mustache
     */
    public function __construct(Mustache_Engine $mustache)
    {
        $this->mustache = $mustache;
    }

    public function indexAction(ServerRequestInterface $request, ResponseInterface  $response)
    {
        $data = [
            'text'=>'Hallo Welt'
        ];
        $body = $this->mustache->render('index',$data);
        $response->getBody()->write($body);

        return $response;
    }
}