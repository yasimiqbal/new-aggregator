<?php

namespace App\Services\V1;

use APIBaseUrls;
use App\Mappers\ArticleMapper;
use App\Repositories\V1\ArticleRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;
use Sources;

class NewsService
{
    /**
     * @var ArticleRepo
     */
    private ArticleRepo $articleRepo;

    /**
     * @var ArticleMapper
     */
    private ArticleMapper $articleMapper;

    public function __construct(ArticleRepo $articleRepo, ArticleMapper $articleMapper)
    {
        $this->articleMapper = $articleMapper;
        $this->articleRepo = $articleRepo;
    }

    /**
     * @var array|array[]
     */
    private array $sources = [
        Sources::NEWS_API => [
            'url' => APIBaseUrls::NEWS_API . 'top-headlines',
            'params' => ['country' => 'us'],
            'key_param' => 'apiKey',
            'key' => 'services.news.keys.newsapi',
            'mapper' => 'mapNewsAPI',
            'response_path' => 'articles',
        ],
        Sources::GUARDIAN => [
            'url' => APIBaseUrls::GUARDIAN . 'search',
            'params' => [],
            'key_param' => 'api-key',
            'key' => 'services.news.keys.guardian',
            'mapper' => 'mapGuardian',
            'response_path' => 'response.results',
        ],
        Sources::NEW_YEAR_TIMES => [
            'url' => APIBaseUrls::NEW_YEAR_TIMES . 'search/v2/articlesearch.json',
            'params' => [],
            'key_param' => 'api-key',
            'key' => 'services.news.keys.nyt',
            'mapper' => 'mapNewYearTimes',
            'response_path' => 'response.docs',
        ],
    ];

    /**
     * @return void
     */
    public function fetchAndStoreArticles(): void
    {
        foreach ($this->sources as $source => $config) {
            $this->fetchAndProcessArticles($source, $config);
        }
    }

    /**
     * @param string $source
     * @param array $config
     * @return void
     */
    private function fetchAndProcessArticles(string $source, array $config): void
    {
        try {
            $params = array_merge($config['params'], [$config['key_param'] => config($config['key'])]);
            $response = $this->fetchFromApi($config['url'], $params);

            if (!empty($response)) {
                $mappedArticles = [];
                $articles = data_get($response, $config['response_path'], []);
                foreach ($articles as $article) {
                    $mappedArticles[] = $this->articleMapper->{$config['mapper']}($article);
                }
                $this->articleRepo->insert($mappedArticles);
            } else {
                throw new \Exception("{$source} API returned an empty response.");
            }
        } catch (GuzzleException|\Exception $e) {
            Log::error("Error fetching from {$source}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * @param string $url
     * @param array $params
     * @return array
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    private function fetchFromApi(string $url, array $params = []): array
    {
        $response = Http::retry(3, 100)->get($url, $params);
        return $response->successful() ? $response->json() : [];
    }
}
