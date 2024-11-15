<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\News\Providers\NewsApiProvider;
use App\Models\Article;
use Carbon\Carbon;

class FetchNewsData extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetches news data and stores it in the database';
    protected $newsProvider;

    public function __construct(NewsApiProvider $newsProvider)
    {
        parent::__construct();
        $this->newsProvider = $newsProvider;
    }

    /**
     * Handle the command.
     *
     * @return void
     */
    public function handle()
    {
        // Define the date range with explicit formatting
        $params = [
            'q' => 'latest'
        ];

        // Fetch articles using the NewsApiProvider
        $articles = $this->newsProvider->getEverything($params);

        // Store articles in the database
        $this->storeArticles($articles);

        // Output a message to indicate that the command has run successfully
        $this->info('News data fetched and stored successfully.');
    }

    /**
     * Store articles in the database.
     *
     * @param array $articles The articles to be stored.
     * @return void
     */
    private function storeArticles(array $articles)
    {

        foreach ($articles as $article) {
            if (isset($article->source->id)) {
                $publishedAt = isset($article->publishedAt) ? date('Y-m-d H:i:s', strtotime($article->publishedAt)) : null;

                Article::updateOrCreate(
                    ['title' => $article->title], // Avoid duplicate entries based on title
                    [
                        'source_id'     => $article->source->id ?? null,
                        'source_name'   => $article->source->name ?? null,
                        'author'        => $article->author ?? null,
                        'title'         => $article->title,
                        'description'   => $article->description ?? null,
                        'url'           => $article->url,
                        'url_to_image'  => $article->urlToImage ?? null,
                        'published_at'  => $publishedAt,
                        'content'       => $article->content ?? null,
                    ]
                );
            }
        }
    }
}
