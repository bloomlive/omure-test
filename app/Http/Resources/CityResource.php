<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin City
 */
class CityResource extends JsonResource
{

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'      => $this->name,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
