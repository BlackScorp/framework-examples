<?php

namespace BlackScorp\Movies\Mustache;

final class LinkStruct
{
    public string $title;
    public bool $active = false;
    public string $routName;
    public array $data;
    public array $queryParams;

    public function __construct(string $title, string $routName,bool $active = false, array $data = [], array $queryParams = [])
    {
        $this->title = $title;
        $this->active = $active;
        $this->routName = $routName;
        $this->data = $data;
        $this->queryParams = $queryParams;
    }
}