<?php

namespace BlackScorp\Movies\Controller;

use BlackScorp\Movies\Middleware\MustacheMiddleware;
use BlackScorp\Movies\Model\VideoModel;
use BlackScorp\Movies\Mustache\Template;
use BlackScorp\Movies\Repository\VideoRepository;
use BlackScorp\Movies\Service\MovieDBApiProvider;
use Mustache_Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class IndexController
{
    private MovieDBApiProvider $movieDBApiProvider;
    private VideoRepository $videoRepository;

    /**
     * @param MovieDBApiProvider $movieDBApiProvider
     */
    public function __construct(MovieDBApiProvider $movieDBApiProvider, VideoRepository $videoRepository)
    {
        $this->movieDBApiProvider = $movieDBApiProvider;
        $this->videoRepository = $videoRepository;
    }


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


    public function addAction(ServerRequestInterface $request, ResponseInterface  $response,array $arguments)
    {
        $movieId = $arguments['id']??null;
        if($movieId){
            $movieArray = $this->movieDBApiProvider->findById($movieId);
            $model = new VideoModel();
            $model->setId($movieArray['id']);
            $model->setTitle($movieArray['title']);
            $model->setOverview($movieArray['overview']);
            $model->setReleaseDate($movieArray['release_date']);

            $model->setImagePath($movieArray['poster_path']);

            $this->videoRepository->add($model);

            //lade
            //speichere
        }

        $response = $response->withHeader('Location','/');
        return $response->withStatus(302);
    }
}