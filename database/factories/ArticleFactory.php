<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'source_name' => $this->faker->company,
            'author' => $this->faker->name,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'url_to_image' => $this->faker->imageUrl(),
            'published_at' => $this->faker->dateTime,
            'content' => $this->faker->text,
        ];
    }
}
