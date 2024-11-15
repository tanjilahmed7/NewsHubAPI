<?php

namespace App\Services\News\Providers;

use App\Services\News\NewsProviderInterface;
use jcobhams\NewsApi\NewsApi;

class NewsApiProvider implements NewsProviderInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new NewsApi(config('services.newsapi.key'));
    }


    /**
     * Retrieves everything from the News API based on the given parameters.
     *
     * @param array $params The parameters for the API request.
     * @return array The response from the API.
     */
    public function getEverything(array $params): array
    {
        $query      = $params['q'] ?? null;
        $from       = $params['from'] ?? null;
        $to         = $params['to'] ?? null;
        $language   = $params['language'] ?? null;
        $sortBy     = $params['sortBy'] ?? null;
        $page       = $params['page'] ?? 1;
        $pageSize   = $params['pageSize'] ?? 10;

        // Pass page and pageSize to the getEverything call
        $response = $this->client->getEverything($query, null, $from, $to, $language, $sortBy, null, null, null, $page, $pageSize);

        return $response->articles ?? [];
    }
}
