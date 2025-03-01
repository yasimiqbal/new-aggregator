<?php

namespace App\Mappers;

use Carbon\Carbon;

class ArticleMapper
{
    /**
     * @param array $article
     * @return array
     */
    public function mapNewsAPI(array $article): array
    {
        $time = Carbon::now();
        return [
            'title' => $article['title'] ?? null,
            'url' => $article['url'] ?? null,
            'description' => $article['content'] ?? $article['description'] ?? null,
            'author' => $article['author'] ?? null,
            'category' => $article['source']['name'] ?? null,
            'source' => 'NewsAPI',
            'published_at' => isset($article['publishedAt']) ? Carbon::parse($article['publishedAt']) : $time,
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }

    /**
     * @param array $article
     * @return array
     */
    public function mapGuardian(array $article): array
    {
        $time = Carbon::now();
        return [
            'title' => $article['webTitle'] ?? null,
            'url' => $article['webUrl'] ?? null,
            'description' => $article['webTitle'] ?? null, // because description is not given by the api
            'author' => null,
            'category' => $article['pillarName'] ?? null,
            'source' => 'Guardian',
            'published_at' => isset($article['webPublicationDate']) ? Carbon::parse($article['webPublicationDate']) : $time,
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }

    /**
     * @param array $article
     * @return array
     */
    public function mapNewYearTimes(array $article): array
    {
        $time = Carbon::now();
        return [
            'title' => $article['headline']['main'] ?? null,
            'url' => $article['web_url'] ?? null,
            'description' => $article['lead_paragraph'] ?? null,
            'author' => $article['byline']['original'] ?? null,
            'category' => $article['type_of_material'] ?? null,
            'source' => 'New York Times',
            'published_at' => isset($article['pub_date']) ? Carbon::parse($article['pub_date']) : $time,
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }
}
