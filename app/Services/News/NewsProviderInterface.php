<?php

namespace App\Services\News;

interface NewsProviderInterface
{
    /**
     * Retrieves everything from the news provider based on the given parameters.
     *
     * @param array $params The parameters for retrieving the news.
     * @return array The array containing the retrieved news.
     */
    public function getEverything(array $params): array;
}
