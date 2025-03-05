<?php

namespace App\Http\Resources\V1\Preference;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="PreferenceCollection",
 *     title="PreferenceCollection",
 *     description="A collection of user preferences",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/PreferenceResource")
 *     )
 * )
 */
class PreferenceCollection extends ResourceCollection
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
            return new PreferenceResource($article, $this->params);
        });
    }
}
