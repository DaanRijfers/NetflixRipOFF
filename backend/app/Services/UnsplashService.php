<?php

namespace App\Services;

use GuzzleHttp\Client;

class UnsplashService
{
    protected $client;
    protected $accessKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->accessKey = env('UNSPLASH_ACCESS_KEY'); // Load the API key from .env
    }

    /**
     * Search for photos on Unsplash.
     *
     * @param string $query The search term (e.g., "nature").
     * @param int $perPage Number of results per page.
     * @return array
     */
    public function searchPhotos($query, $perPage = 10)
    {
        $response = $this->client->get('https://api.unsplash.com/search/photos', [
            'headers' => [
                'Authorization' => 'Client-ID ' . $this->accessKey,
            ],
            'query' => [
                'query' => $query,
                'per_page' => $perPage,
            ],
        ]);

        return json_decode($response->getBody(), true); // Return the API response as an array
    }
}