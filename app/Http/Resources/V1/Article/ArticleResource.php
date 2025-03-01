<?php

namespace App\Http\Resources\V1\Article;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
