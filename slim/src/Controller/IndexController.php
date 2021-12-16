<?php

namespace BlackScorp\Movies\Controller;

use BlackScorp\Movies\Middleware\MustacheMiddleware;
use BlackScorp\Movies\Model\VideoModel;
use BlackScorp\Movies\Mustache\LinkStruct;
use BlackScorp\Movies\Mustache\Template;
use BlackScorp\Movies\Repository\VideoRepository;
use BlackScorp\Movies\Service\MovieDBApiProvider;
use BlackScorp\Movies\View\VideoView;
use Mustache_Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class IndexController
{
    public const ROUTE_NAME_INDEX = 'index';
    public const ROUTE_NAME_WISH_LIST = 'wishlist';

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


    public function wishlistAction(ServerRequestInterface $request, ResponseInterface  $response)
    {
        return $this->renderMovies($request,$response,false);
    }

    public function indexAction(ServerRequestInterface $request, ResponseInterface  $response)
    {
        return $this->renderMovies($request,$response);
    }

    private function renderMovies(ServerRequestInterface $request, ResponseInterface  $response,bool $owned = true): ResponseInterface
    {
        /** @var Template $template */
        $template = $request->getAttribute(MustacheMiddleware::TEMPLATE_KEY);

        $template->setTemplate('index');

        $videoList = [];
        $imageBaseUrl = $this->movieDBApiProvider->getImageBaseUrl();

        if($owned){
            $videoEntityCollection = $this->videoRepository->findOwned();
        }else{
            $videoEntityCollection = $this->videoRepository->findWished();
        }


        foreach($videoEntityCollection as $videEntity){
            $videoList[]=VideoView::createFromEntity($videEntity);
        }

        $linkStructs = [];
        $linkStructs[]=new LinkStruct('Startseite',self::ROUTE_NAME_INDEX);
        $linkStructs[]=new LinkStruct('Wunschliste',self::ROUTE_NAME_WISH_LIST);

        $template->setData([
            'text' => 'Hello World!',
            'videoList'=>$videoList,
            'imageBaseUrl'=>$imageBaseUrl,
            'navi'=>$linkStructs
        ]);

        return $response;
    }
    public function errorAction(ServerRequestInterface $request, ResponseInterface  $response)
    {
        /** @var Template $template */
        $template = $request->getAttribute(MustacheMiddleware::TEMPLATE_KEY);

        $template->setTemplate('error');

        return $response;
    }
    public function addAction(ServerRequestInterface $request, ResponseInterface  $response,array $arguments)
    {
        $movieId = $arguments['id']??null;
        $owned = !isset($arguments['wish']);
        if($movieId){
            $movieArray = $this->movieDBApiProvider->findById($movieId);
            $model = new VideoModel();
            $model->setId($movieArray['id']);
            $model->setTitle($movieArray['title']);
            $model->setOverview($movieArray['overview']);
            $model->setReleaseDate($movieArray['release_date']);

            $model->setOwned($owned);
            $model->setImagePath($movieArray['poster_path']);

            $this->videoRepository->add($model);

            //lade
            //speichere
        }

        $response = $response->withHeader('Location','/');
        return $response->withStatus(302);
    }
}