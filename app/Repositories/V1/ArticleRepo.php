<?php

namespace App\Repositories\V1;

use App\Models\Article;
use App\Repositories\BaseRepo;

class ArticleRepo extends BaseRepo
{
    /**
     * @var Article
     */
    private Article $article;

    /**
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        parent::__construct($article);
        $this->article = $article;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getArticles($params): mixed
    {
        return $this->model()->query()
            ->when(isset($params['q']), function ($query) use ($params) {
                $query->where(function ($q) use ($params) {
                    $q->where('title', 'like', '%' . $params['q'] . '%')
                        ->orWhere('description', 'like', '%' . $params['q'] . '%')
                        ->orWhere('author', 'like', '%' . $params['q'] . '%');
                });
            })
            ->when(!empty($params['date']), function ($query) use ($params) {
                $query->whereDate('published_at', $params['date']);
            })
            ->when(!empty($params['category']), function ($query) use ($params) {
                $query->whereDate('category', 'like', '%' . $params['category'] . '%');
            })
            ->when(!empty($params['source']), function ($query) use ($params) {
                $query->whereDate('source', 'like', '%' . $params['source'] . '%');
            });
    }

    /**
     * @param $preferences
     * @return mixed
     */
    public function fetchUserNewsFeed($preferences): mixed
    {
        return $this->model()->query()
            ->when(!empty($preferences['sources']), function ($query) use ($preferences) {
                $query->whereIn('source', $preferences['sources']);
            })
            ->when(!empty($preferences['categories']), function ($query) use ($preferences) {
                $query->orWhereIn('category', $preferences['categories']);
            })
            ->when(!empty($preferences['authors']), function ($query) use ($preferences) {
                $query->orWhereIn('author', $preferences['authors']);
            });
    }
}
