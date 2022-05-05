<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{

    public function definition()
    {
        return [
            'name'      => $this->faker->city,
            'country' => $this->faker->countryCode,
            'longitude' => $this->faker->randomFloat(max: 100),
            'latitude'  => $this->faker->randomFloat(max: 100),
        ];
    }
}
