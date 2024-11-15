<?php

namespace App\Services\News;

interface NewsProviderInterface
{
    public function getEverything(array $params): array;
}
