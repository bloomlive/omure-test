<?php

namespace App\Jobs\OpenWeatherMap;

use App\Exceptions\OpenWeatherMap\NoResultsException;
use App\Exceptions\OpenWeatherMap\RequestFailedException;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class StoreOrUpdateForecastJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    const API_URI = 'https://api.openweathermap.org/data/2.5/onecall';

    private City $city;

    public function __construct(City $city)
    {
        $this->city = $city;
    }

    public function handle()
    {
        $request = Http::get(
            self::API_URI,
            [
                'appid'   => config('openweathermap.api.key'),
                'lat'     => $this->city->latitude,
                'lon'     => $this->city->longitude,
                'exclude' => 'current,minutely,daily,alerts',
                'units'   => 'metric',
            ]
        );

        if ($request->failed()) {
            throw new RequestFailedException($request->reason(), $request->status());
        }

        if (empty($request->json()['hourly'])) {
            throw new NoResultsException(__('No results were found.'));
        }

        $hours = collect($request->json()['hourly']);

        DB::transaction(function () use ($hours) {
            $hours->each(function ($hour) {
                $this->city->forecasts()->updateOrCreate([
                    'start_time' => Carbon::parse($hour['dt']),
                ], [
                    'start_time'  => $hour['dt'],
                    'temp'        => $hour['temp'],
                    'feels_like'  => $hour['feels_like'],
                    'pressure'    => $hour['pressure'],
                    'humidity'    => $hour['humidity'],
                    'dew_point'   => $hour['dew_point'],
                    'uvi'         => $hour['uvi'],
                    'clouds'      => $hour['clouds'],
                    'visibility'  => $hour['visibility'],
                    'wind_speed'  => $hour['wind_speed'],
                    'wind_deg'    => $hour['wind_deg'],
                    'wind_gust'   => $hour['wind_gust'],
                    'description' => $hour['weather'],
                    'pop'         => $hour['pop'],
                ]);
            });
        });
    }
}
