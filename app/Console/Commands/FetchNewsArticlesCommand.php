<?php

namespace App\Console\Commands;

use App\Services\V1\NewsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchNewsArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch News Articles from sources';


    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        parent::__construct();
        $this->newsService = $newsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("Start to fetch articles");
        $this->newsService->fetchAndStoreArticles();
        Log::info("Successfully fetch articles");
    }
}
