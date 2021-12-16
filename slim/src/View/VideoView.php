<?php

namespace BlackScorp\Movies\View;

use BlackScorp\Movies\Model\VideoModel;

final class VideoView
{
    public int $id;
    public string $title;
    public string $overview;
    public string $releaseDate;
    public string $imagePath;
    public string $rentedTo = '';
    public bool $isOwned = false;

    public static function createFromSearchResult(array $searchResult):self{
        $videoView = new self();

        $videoView->id = $searchResult['id'];
        $videoView->title = $searchResult['title'];
        $videoView->overview =$searchResult['overview'];
        $videoView->imagePath = (string)$searchResult['poster_path'];
        if($searchResult['release_date']){
            $videoView->releaseDate = (\DateTime::createFromFormat('Y-m-d',$searchResult['release_date']))->format('d.M.Y');
        }

        return $videoView;
    }

    public static function createFromEntity(VideoModel $model):self{
        $view = new self();
        $view->id = $model->getId();
        $view->title = $model->getTitle();
        $view->overview = $model->getOverview();
        $view->imagePath = $model->getImagePath();
        $view->releaseDate = (\DateTime::createFromFormat('Y-m-d',$model->getReleaseDate()))->format('d.M.Y');
        $view->rentedTo = $model->getRentedTo();
        $view->isOwned = $model->isOwned();

        return  $view;
    }
}