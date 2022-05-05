<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\City;
use App\Models\Forecast;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ForecastsControllerTest extends TestCase
{

    use LazilyRefreshDatabase;

    public function test_guest_is_required_to_specify_a_date()
    {
        $response = $this->getJson(route('api.forecasts'));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'date',
                ],
            ]);
    }

    public function test_guest_cannot_query_backwards()
    {
        $city = City::factory()->create();

        Forecast::factory()
            ->for($city)
            ->startTime(Carbon::now())
            ->withIncreasingStartTime()
            ->count(48)
            ->create();


        $response = $this->getJson(route('api.forecasts',['date' => now()->subHour()->toDateTimeString()]));

        $response
            ->assertStatus(404)
            ->assertJsonStructure(['message']);
    }

    public function test_guest_can_request_city()
    {
        $city = City::factory()->create();

        Forecast::factory()
            ->for($city)
            ->startTime(Carbon::now())
            ->withIncreasingStartTime()
            ->count(48)
            ->create();


        $response = $this->getJson(route('api.forecasts',[
            'date' => now()->addHour()->toDateTimeString(),
            'city' => true
        ]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                    'data' => [
                        [
                            'city' => [
                                'name',
                                'latitude',
                                'longitude'
                            ],
                            'start_time',
                            'temp',
                            'feels_like',
                        ]
                    ],
                ]
            );
    }

    public function test_guest_can_query_all_cities_at_once()
    {
        $city = City::factory()->create();
        Forecast::factory()
            ->for($city)
            ->startTime(Carbon::now())
            ->create();

        $city = City::factory()->create();
        Forecast::factory()
            ->for($city)
            ->startTime(Carbon::now())
            ->create();

        $city = City::factory()->create();
        Forecast::factory()
            ->for($city)
            ->startTime(Carbon::now())
            ->create();

        $response = $this->getJson(route('api.forecasts',['date' => now()->addHour()->toDateTimeString()]));

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                    'data' => [
                        [
                            'start_time',
                            'temp',
                            'feels_like',
                        ]
                    ],
                ]
            );
    }

    public function test_guest_can_query_forecasts()
    {
        $city = City::factory()->create();

        Forecast::factory()
            ->for($city)
            ->startTime(Carbon::now())
            ->withIncreasingStartTime()
            ->count(48)
            ->create();

        $response = $this->getJson(route('api.forecasts',['date' => now()->addHour()->toDateTimeString()]));



        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                    'data' => [
                        [
                            'start_time',
                            'temp',
                            'feels_like',
                        ]
                    ],
                ]
            );
    }
}
