<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Carbon\Carbon;

class NewYorkTimesService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.nytimes.com/svc/topstories/v2/';

    public function __construct()
    {
        $this->apiKey = config('services.nyt.key');
    }

    public function fetchLatestArticles($section = 'technology')
    {

        $keywords = ['home','science','us','world'];
        $section = $keywords[array_rand($keywords)];
        // Make the request to the New York Times Top Stories API
        $response = Http::get($this->baseUrl . $section . '.json', [
            'api-key' => $this->apiKey,
        ]);

        // Decode the JSON response
        $articles = $response->json()['results'];
        $articlesToInsert = [];
        // Save the articles to the database
        foreach ($articles as $article) {
            $byline = $article['byline'] ?? 'Unknown';
            $category = Category::updateOrCreate(['name'=> $section],['name'=>$section]);
            $source = Source::updateOrCreate(['name'=> 'New York Times'],['name'=> 'New York Times']);
            $author = Author::updateOrCreate(['name'=> $byline],['name'=> $byline]);
            $articlesToInsert[] = [
                'title' => $article['title'],
                'description' => $article['abstract'],
                'url' => $article['url'],
                'thumbnail_url' => isset($article['multimedia'][0]['url']) ? $article['multimedia'][0]['url'] : '',
                'author_id' => $author->id,
                'source_id' => $source->id,
                'category_id' => $category->id,
                'published_at' => Carbon::parse($article['published_date']),
            ];
        }
        $articles = Article::upsert($articlesToInsert, ['url'], ['title', 'description','thumbnail_url', 'author_id', 'source_id','category_id', 'published_at']);

        return $articles;
    }
}
