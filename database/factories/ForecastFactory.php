<?php

namespace Database\Factories;

use App\Models\Forecast;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ForecastFactory extends Factory
{

    protected $model = Forecast::class;

    protected static Carbon $startDatetime;

    public function definition(): array
    {
        return [
            'start_time'  => $this->faker->dateTime(),
            'temp'        => $this->faker->randomFloat(max: 100),
            'feels_like'  => $this->faker->randomFloat(max: 100),
            'pressure'    => $this->faker->randomFloat(max: 100),
            'humidity'    => $this->faker->randomFloat(max: 100),
            'dew_point'   => $this->faker->randomFloat(max: 100),
            'uvi'         => $this->faker->randomFloat(max: 100),
            'clouds'      => $this->faker->randomFloat(max: 100),
            'visibility'  => $this->faker->randomFloat(max: 100),
            'wind_speed'  => $this->faker->randomFloat(max: 100),
            'wind_deg'    => $this->faker->randomFloat(max: 100),
            'wind_gust'   => $this->faker->randomFloat(max: 100),
            'pop'         => $this->faker->randomFloat(max: 100),
            'description' => $this->faker->shuffleArray([]),
        ];
    }

    public function startTime(Carbon $carbon): static
    {
        static::$startDatetime = $carbon->startOfHour();

        return $this;
    }

    public function withIncreasingStartTime()
    {
        return $this->state(function (array $attributes) {
            return [
                'start_time' => static::$startDatetime->addHour(),
            ];
        });
    }
}
