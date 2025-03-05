<?php

namespace App\Http\Resources\V1\Article;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ArticleResource",
 *     title="ArticleResource",
 *     description="Article resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Breaking News: Laravel Updates"),
 *     @OA\Property(property="content", type="string", example="Lorem ipsum dolor sit amet..."),
 *     @OA\Property(property="author", type="string", example="John Doe"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2025-03-06T12:00:00Z")
 * )
 */
class ArticleResource extends JsonResource
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
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id ?? null,
            'title' => $this->title ?? null,
            'description' => $this->description ?? null,
            'url' => $this->url ?? null,
            'category' => $this->category ?? null,
            'author' => $this->author ?? null,
            'source' => $this->source ?? null,
            'published_at' => !empty($this->published_at) ? Carbon::parse($this->published_at)->format('Y-m-d H:i:s') : null,
        ];

        return $data;
    }
}
