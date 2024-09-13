<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Carbon\Carbon;

class NewsApiService
{
    protected $apiKey;
    protected $baseUrl = 'https://newsapi.org/v2/';

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
    }

    public function fetchLatestArticles($keyword = 'technology', $pageSize = 100)
    {
        $category = Category::inRandomOrder()->first();
        $keyword = $category->name;
        // Make the request to the NewsAPI
        $response = Http::get($this->baseUrl . 'everything', [
            'q' => $keyword,
            'pageSize' => $pageSize,
            'from'  => Carbon::yesterday(),
            'to'  => Carbon::today(),
            'sortBy' => 'popularity',
            'apiKey' => $this->apiKey,
        ]);

        // Decode the JSON response
        $articles = isset($response->json()['articles']) ? $response->json()['articles'] : [];

        // Save the articles to the database
        $articlesToInsert = [];
        foreach ($articles as $article) {
            $author_name = $article['author'] ?? 'Unknown';
            $source_name = $article['source']['name'];
            $source = Source::updateOrCreate(['name'=> $source_name],['name'=> $source_name]);
            $author = Author::updateOrCreate(['name'=> $author_name],['name'=> $author_name]);

            $articlesToInsert[] = [
                'title' => $article['title'],
                'description' => $article['description'],
                'url' => $article['url'],
                'thumbnail_url' => isset($article['urlToImage']) ? $article['urlToImage'] : '',
                'author_id' => $author->id,
                'source_id' => $source->id,
                'category_id' => $category->id,
                'published_at' => Carbon::parse($article['publishedAt']),
            ];
        }
        $articles = Article::upsert($articlesToInsert, ['url'], ['title', 'description','thumbnail_url', 'author_id', 'source_id','category_id', 'published_at']);

        return $articles;
    }
}
