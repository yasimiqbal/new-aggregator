<?php

namespace App\Http\Resources\V1\Article;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="ArticleCollection",
 *     title="ArticleCollection",
 *     description="A collection of articles",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ArticleResource")
 *     )
 * )
 */
class ArticleCollection extends ResourceCollection
{
    /**
     * @var array|mixed
     */
    protected $params;

    /**
     * @param $resource
     * @param $params
     */
    public function __construct($resource, $params = [])
    {
        parent::__construct($resource);
        $this->params = !empty($params) ? $params : request()->all();
    }

    /**
     * @param $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($article) {
                return new ArticleResource($article, $this->params);
            });
    }
}
