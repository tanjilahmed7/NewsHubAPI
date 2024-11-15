<?php

namespace App\Services\News;

class NewsService
{
    protected $providers;

    /**
     * Constructor for the NewsService class.
     *
     * @param array $providers An array of providers.
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    /**
     * Retrieves the top headlines based on the given parameters.
     *
     * @param array $params The parameters for retrieving the top headlines.
     * @return array The array of top headlines.
     */
    public function getTopHeadlines(array $params): array
    {
        $results = [];
        foreach ($this->providers as $provider) {
            $results = array_merge($results, $provider->getTopHeadlines($params));
        }
        return $results;
    }

    /**
     * Retrieves everything from the news service based on the given parameters.
     *
     * @param array $params The parameters for retrieving the news.
     * @return array The retrieved news.
     */
    public function getEverything(array $params): array
    {
        $results = [];
        foreach ($this->providers as $provider) {
            $results = array_merge($results, $provider->getEverything($params));
        }
        return $results;
    }

    /**
     * Retrieves the sources based on the given parameters.
     *
     * @param array $params The parameters for retrieving the sources.
     * @return array The array of sources.
     */
    public function getSources(array $params): array
    {
        $results = [];
        foreach ($this->providers as $provider) {
            $results = array_merge($results, $provider->getSources($params));
        }
        return $results;
    }
}
