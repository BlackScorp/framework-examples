<?php

namespace BlackScorp\Movies\Repository;

use BlackScorp\Movies\Model\VideoModel;

interface VideoRepository
{
    public function add(VideoModel $video): void;

    /**
     * @return array<VideoModel>
     */
    public function findOwned(): array;

    public function findWished();
}