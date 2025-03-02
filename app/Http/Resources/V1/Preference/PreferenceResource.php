<?php

namespace App\Http\Resources\V1\Preference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PreferenceTypes;

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
