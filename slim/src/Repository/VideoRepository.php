<?php

namespace BlackScorp\Movies\Repository;

use BlackScorp\Movies\Model\VideoModel;

interface VideoRepository
{
    public function add(VideoModel $video): void;
}