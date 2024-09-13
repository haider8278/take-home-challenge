<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Carbon\Carbon;

class GuardianApiService
{
    protected $apiKey;
    protected $baseUrl = 'https://content.guardianapis.com/';

    public function __construct()
    {
        $this->apiKey = config('services.guardian.key');
    }

    public function fetchLatestArticles($pageSize = 200)
    {
        // Make the request to The Guardian API
        $response = Http::get($this->baseUrl . 'search', [
            //'q' => $keyword,
            'page-size' => $pageSize,
            'from-date' => Carbon::yesterday(),
            'to-date'   => Carbon::today(),
            'show-fields' => 'headline,byline,body,firstPublicationDate,thumbnail',
            'api-key' => $this->apiKey,
        ]);

        // Decode the JSON response
        $articles = $response->json()['response']['results'];
        $articlesToInsert = [];
        // Save the articles to the database
        foreach ($articles as $article) {
            $byline = $article['fields']['byline'] ?? 'Unknown';
            $category_name = isset($article['pillarName']) ? $article['pillarName'] : 'No Category';
            $category = Category::updateOrCreate(['name'=> $category_name],['name'=>$category_name]);
            $source = Source::updateOrCreate(['name'=> 'The Guardian'],['name'=> 'The Guardian']);
            $author = Author::updateOrCreate(['name'=> $byline],['name'=> $byline]);

            $articlesToInsert[] = [
                'title' => $article['fields']['headline'],
                'description' => $article['fields']['body'],
                'url' => $article['webUrl'],
                'thumbnail_url' => isset($article['fields']['thumbnail']) ? $article['fields']['thumbnail'] : '',
                'author_id' => $author->id,
                'source_id' => $source->id,
                'category_id' => $category->id,
                'published_at' => Carbon::parse($article['fields']['firstPublicationDate']),
            ];
        }
        $articles = Article::upsert($articlesToInsert, ['url'], ['title', 'description','thumbnail_url', 'author_id', 'source_id','category_id', 'published_at']);

        return $articles;
    }
}
