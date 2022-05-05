<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ForecastResource extends JsonResource
{

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'city' => CityResource::make($this->whenLoaded('city')),
            $this->merge(
                Arr::except(parent::toArray($request), [
                    'id',
                ])
            ),
        ];
    }
}
