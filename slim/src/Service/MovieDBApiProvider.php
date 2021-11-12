<?php

namespace BlackScorp\Movies\Service;

use GuzzleHttp\Client;

final class MovieDBApiProvider
{
    private Client $client;
    private string $apiKey;

    /**
     * @param Client $client
     */
    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }
    public function searchMovies(string $query):array {
        $response = $this->client->get('search/movie',[
           'query'=>[
               'api_key'=>$this->apiKey,
               'query'=>$query
           ]
        ]);
        if($response->getStatusCode() === 200){
            $data = json_decode((string)$response->getBody(), true);
            return $data['results'];
        }
        return [];
    }
}