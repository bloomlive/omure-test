<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CitiesSeeder extends Seeder
{

    const API_URI = 'http://api.openweathermap.org/geo/1.0/direct';

    const CITIES = [
        'New York',
        'London',
        'Paris',
        'Berlin',
        'Tokyo',
    ];

    public function run()
    {
        DB::transaction(function () {
            foreach (self::CITIES as $city) {
                $request = Http::get(self::API_URI,
                    [
                        'q'     => $city,
                        'appid' => config('openweathermap.api.key'),
                    ]
                );

                $data = $request->json();

                if (!isset($data[0])) {
                    throw new \Exception('No data found.');
                }

                City::query()->create([
                    'name'      => $data[0]['name'],
                    'latitude'  => $data[0]['lat'],
                    'longitude' => $data[0]['lon'],
                    'country'   => $data[0]['country'],
                ]);
            }
        });
    }
}
