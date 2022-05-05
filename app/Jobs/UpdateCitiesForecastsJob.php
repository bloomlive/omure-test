<?php

namespace App\Jobs;

use App\Events\ForecastUpdatedEvent;
use App\Jobs\OpenWeatherMap\StoreOrUpdateForecastJob;
use App\Models\City;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;

class UpdateCitiesForecastsJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $batch = Bus::batch([]);

        City::query()
            ->chunk(50, function ($cities) use ($batch) {
                foreach ($cities as $city) {
                    $batch->add(new StoreOrUpdateForecastJob($city));
                }
            });

        $batch->then(function () {
            ForecastUpdatedEvent::dispatch(Carbon::parse(\LARAVEL_START));
        });

        $batch->dispatch();
    }
}
