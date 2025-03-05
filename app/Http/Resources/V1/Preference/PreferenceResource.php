<?php

namespace App\Http\Resources\V1\Preference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PreferenceTypes;

/**
 * @OA\Schema(
 *     schema="PreferenceResource",
 *     title="PreferenceResource",
 *     description="User preference resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Technology"),
 *     @OA\Property(property="source", type="string", example="TechCrunch", nullable=true),
 *     @OA\Property(property="category", type="string", example="Science", nullable=true),
 *     @OA\Property(property="author", type="string", example="John Doe", nullable=true)
 * )
 */
class PreferenceResource extends JsonResource
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
        ];

        $typeMappings = [
            PreferenceTypes::SOURCE => 'source',
            PreferenceTypes::CATEGORY => 'category',
            PreferenceTypes::AUTHOR => 'author',
        ];

        if (isset($this->type) && isset($typeMappings[$this->type])) {
            $data[$typeMappings[$this->type]] = $typeMappings[$this->type];
        }

        return $data;
    }

}
