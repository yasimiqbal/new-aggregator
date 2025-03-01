<?php

namespace App\Services\V1;

use App\Repositories\V1\ArticleRepo;

class ArticleService
{
    /**
     * @var ArticleRepo
     */
    private ArticleRepo $articleRepo;

    /**
     * @param ArticleRepo $articleRepo
     */
    public function __construct(ArticleRepo $articleRepo)
    {
        $this->articleRepo = $articleRepo;
    }

    public function getArticles($params)
    {
        return $this->articleRepo->getArticles($params);
    }

    /**
     * @param int $id
     * @return object
     */
    public function getArticleById(int $id): object
    {
        return $this->articleRepo->find($id);
    }

}
