<?php

namespace App\Console\Commands;

use App\Services\GuardianApiService;
use Illuminate\Console\Command;
use App\Services\NewsApiService;
use App\Services\NewYorkTimesService;

class ScrapeNewsArticles extends Command
{
    // Command name
    protected $signature = 'news:scrape';

    // Command description
    protected $description = 'Scrape news articles from NewsAPI, The Guardian, and New York Times';

    protected $newsApiService;
    protected $guardianApiService;
    protected $newYorkTimesService;


    // Inject NewsApiService
    public function __construct(NewsApiService $newsApiService, GuardianApiService $guardianApiService, NewYorkTimesService $newYorkTimesService)
    {
        parent::__construct();
        $this->newsApiService = $newsApiService;
        $this->guardianApiService = $guardianApiService;
        $this->newYorkTimesService = $newYorkTimesService;
    }

    // Execute the command
    public function handle()
    {
        $this->info('Fetching latest articles from NewsAPI...');
        $newsApiCount = $this->newsApiService->fetchLatestArticles();
        $this->info("Successfully scraped $newsApiCount articles from NewsAPI.");

        $this->info('Fetching latest articles from The Guardian...');
        $guardianCount = $this->guardianApiService->fetchLatestArticles();
        $this->info("Successfully scraped $guardianCount articles from The Guardian.");

        $this->info('Fetching latest articles from The New York Times...');
        $nytCount = $this->newYorkTimesService->fetchLatestArticles();
        $this->info("Successfully scraped $nytCount articles from The New York Times.");
    }
}
