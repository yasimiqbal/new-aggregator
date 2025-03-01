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
}
