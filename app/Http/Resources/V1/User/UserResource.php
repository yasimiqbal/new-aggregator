<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="User details",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com")
 * )
 */
class UserResource extends JsonResource
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
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];

        return $data;
    }
}
