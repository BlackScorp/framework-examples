<?php

namespace BlackScorp\Movies\Controller;

use BlackScorp\Movies\Middleware\MustacheMiddleware;
use BlackScorp\Movies\Mustache\Template;
use BlackScorp\Movies\Service\MovieDBApiProvider;
use BlackScorp\Movies\View\VideoView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SearchController
{
    private MovieDBApiProvider $movieDBApiProvider;

    /**
     * @param MovieDBApiProvider $movieDBApiProvider
     */
    public function __construct(MovieDBApiProvider $movieDBApiProvider)
    {
        $this->movieDBApiProvider = $movieDBApiProvider;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface  $response){
         /** @var Template $template */
         $template = $request->getAttribute(MustacheMiddleware::TEMPLATE_KEY);
         $template->setTemplate('search');

         $formData = $request->getParsedBody();
         $searchTerm = $formData['search'];

         $searchResults = $this->movieDBApiProvider->searchMovies($searchTerm);
         $imageBaseUrl = $this->movieDBApiProvider->getImageBaseUrl();
        $videoList = [];
        foreach($searchResults as $item){
            $videoList[]=VideoView::createFromSearchResult($item);
        }

         $template->setData([
             'imageBaseUrl'=>$imageBaseUrl,
             'videoList'=>$videoList,
             'searchTerm'=>$searchTerm
         ]);
         return $response;
     }
}