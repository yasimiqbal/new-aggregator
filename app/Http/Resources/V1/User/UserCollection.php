<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="UserCollection",
 *     title="User Collection",
 *     description="A collection of user resources",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/UserResource")
 *     )
 * )
 */
class UserCollection extends ResourceCollection
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
            return new UserResource($article, $this->params);
        });
    }
}
